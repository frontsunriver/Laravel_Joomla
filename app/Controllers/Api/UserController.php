<?php

namespace App\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\TermRelation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Sentinel;

class UserController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
    }

    public function updateProfile(Request $request){
        $token = $request->bearerToken();
        $user_id = $this->model->getUserIDByToken($token);
        if($user_id){
            $args = [
                'first_name' => $request->post('first_name'),
                'last_name' => $request->post('last_name'),
                'mobile' => $request->post('mobile'),
                'location' => $request->post('location'),
                'address' => $request->post('address'),
                'description' => $request->post('description'),
            ];

            $user_model = new User();
            $updated = $user_model->updateUser($user_id, $args);
            if (!is_null($updated)) {
                return $this->sendJson([
                    'status' => 1,
                    'message' => __('Updated successfully')
                ]);
            } else {
                return $this->sendJson([
                    'status' => 0,
                    'message' => __('Can not update this user. Try again!')
                ]);
            }
        }
        return $this->sendJson([
            'status' => 0,
            'message' => __('This user is invalid')
        ]);
    }

    public function getCurrentUser(Request $request){
        $token = $request->bearerToken();
        $user_id = $this->model->getUserIDByToken($token);
        if($user_id){
            $user = get_user_by_id($user_id)->toArray();
	        $user['meta'] = $this->model->getUserAllMeta($user_id)->toArray();
            return $this->sendJson([
                'status' => 1,
                'message' => __('Success'),
                'data' => $user
            ]);
        }
        return $this->sendJson([
            'status' => 0,
            'message' => __('Data is invalid')
        ]);
    }

    public function changePassword(Request $request){
        $token = $request->bearerToken();
        $user_id = $this->model->getUserIDByToken($token);

        if($user_id) {
            $user = get_user_by_id($user_id);
            if (!$user) {
                return $this->sendJson([
                    'status' => 0,
                    'message' => __('This user does not exist')
                ]);
            }

            $validator = Validator::make($request->all(), [
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);

            if ($validator->fails()) {
                return $this->sendJson([
                    'status' => 0,
                    'message' => $validator->errors()
                ]);
            } else {
                $password = trim($request->post('password'));
                $credentials = [
                    'password' => $password,
                ];
                Sentinel::update($user, $credentials);
                return $this->sendJson([
                    'status' => 1,
                    'message' => __('Updated password successfully')
                ]);
            }
        }
        return $this->sendJson([
            'status' => 0,
            'message' => __('Data is invalid')
        ]);
    }

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|exists:users,email',
            ],
            [
                'email.required' => __('The email is required'),
                'email.exists' => __('The email does not exist'),
            ]
        );

        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'message' => $validator->errors()
            ]);
        }

        $credentials = [
            'login' => request()->get('email'),
        ];

        $user = Sentinel::findByCredentials($credentials);

        if (is_null($user)) {
            return $this->sendJson([
                'status' => 0,
                'message' => __('The email does not exist')
            ]);
        } else {
            $password = createPassword(32);
            $credentials = [
                'password' => $password,
            ];

            $user = Sentinel::update($user, $credentials);

            if (!$user) {
                return $this->sendJson([
                    'status' => 0,
                    'message' => __('Can not reset password for this account. Try again!')
                ]);
            } else {
                $subject = sprintf(__('[%s] You have changed the password'), get_option('site_name'));
                $content = view('frontend.email.reset-password', ['user' => $user, 'password' => $password])->render();
                $sent = send_mail('', '', $user->email, $subject, $content);
                if (!$sent) {
                    return $this->sendJson([
                        'status' => 0,
                        'message' => __('Can not send email.')
                    ]);
                }
                return $this->sendJson([
                    'status' => 1,
                    'message' => __('Success! Please check your email for a new password.')
                ]);
            }
        }
    }

    public function logout(Request $request){
		$token = $request->bearerToken();
        $this->model->deleteUserMetaByWhere([
            'meta_key' => 'access_token',
            'meta_value' => $token
        ]);
        return $this->sendJson([
            'status' => 1,
            'message' => __('Successfully logged out')
        ]);
    }

    public function register(Request $request){
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'message' => $validator->errors()
            ]);
        }

	    $credentials = [
		    'email' => $request->post('email'),
		    'password' => $request->post('password'),
		    'first_name' => $request->post('first_name'),
		    'last_name' => $request->post('last_name')
	    ];

	    $new_user = create_new_user($credentials);

	    if(!$new_user['status']){
		    return $this->sendJson([
			    'status' => 0,
			    'message' => __('Can not create new user')
		    ]);
	    }else{
		    return $this->sendJson([
			    'status' => 1,
			    'message' => __('Registered successfully')
		    ]);
	    }
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'message' => $validator->errors()
            ]);
        }
        $data = parse_request($request, array_keys($rules));

        try {

            $user = Sentinel::authenticate($data, true);

        } catch (NotActivatedException $e) {
            return $this->sendJson([
                'status' => 0,
                'message' => $e->getMessage()
            ]);

        } catch (ThrottlingException $e) {
            return $this->sendJson([
                'status' => 0,
                'message' => $e->getMessage()
            ]);

        }

        if (isset($user) && is_object($user)) {

            $token = create_api_token($user->getUserId());
            update_user_meta($user->getUserId(), 'access_token', $token);

            return $this->sendJson([
                'status' => 1,
                'message' => __('Logged in successfully.'),
                'token_code' => $token
            ]);
        } else {
            return $this->sendJson([
                'status' => 0,
                'message' => __('The email or password is incorrect')
            ]);
        }
    }
}

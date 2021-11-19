<?php

namespace App\Controllers;

use App\Controllers\Services\CarController;
use App\Controllers\Services\ExperienceController;
use App\Controllers\Services\HomeController;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use Sentinel;

class UserController extends Controller
{
    public function __construct()
    {
        add_action('hh_dashboard_breadcrumb', [$this, '_addCreateUserButton']);
    }

    public function _afterRegisterPartner(Request $request, $return)
    {
        $return = json_decode(base64_decode($return));
        if (is_object($return)) {
            $user_id = $return->user_id;
            $timestamp = $return->timestamp;
            if (verify_time($timestamp, 5)) {
                return view('frontend.welcome-user', ['user_id' => $user_id]);
            }
        }
        return redirect(url('/'));
    }

    public function _approveUser(Request $request)
    {
        $user_id = request()->get('userID');
        $user_encrypt = request()->get('userEncrypt');
        if (!hh_compare_encrypt($user_id, $user_encrypt)) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }
        $user = get_user_by_id($user_id);
        if (!$user) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }
        $user_model = new User();

        $request = $user->request;
        if (empty($request)) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('There is no request from this account')
            ]);
        }

        if ($request == 'request_a_partner') {
            $update = $user_model->updateUserRole($user_id, 2);
        } elseif ($request == 'request_a_customer') {
            $update = $user_model->updateUserRole($user_id, 3);
        }
        if ($update) {
            $request_flag = false;

            $activation = Activation::create($user);
            if (is_object($activation)) {
                $activation = Activation::complete($user, $activation->code);
                if ($activation) {
                    $request_flag = true;
                }
            }
            if (!$request_flag) {
                return $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Can not active this user. Try again!')
                ]);
            }
            $user_model->updateUser($user_id, ['request' => 'approved']);
            do_action('hh_approved_partner', $user, $request);

            return $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Approved successfully'),
                'reload' => true
            ]);
        }
        return $this->sendJson([
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Has error when approve this user.')
        ]);

    }

    public function _getPartnerInfo(Request $request)
    {
        $user_id = request()->get('userID');
        $user_encrypt = request()->get('userEncrypt');
        if (!hh_compare_encrypt($user_id, $user_encrypt)) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }
        $user = get_user_by_id($user_id);
        if (!$user) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }

        return $this->sendJson([
            'status' => 1,
            'html' => view('dashboard.components.partner-info', ['user' => $user])->render(),
            'message' => __('Successful')
        ]);
    }

    public function _becomeAHostPost(Request $request)
    {
        if (get_option('enable_partner_registration', 'on') == 'off') {
            return $this->sendJson([
                'status' => 0,
                'message' => view('common.alert', ['type' => 'danger', 'message' => __('This featured is not available')])->render()
            ]);
        }
        $validator = Validator::make($request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'gender' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'city' => 'required',
                'term_condition' => 'required'
            ],
            [
                'first_name.required' => __('The First Name is required'),
                'last_name.required' => __('The Last Name is required'),
                'gender.required' => __('The Gender is required'),
                'phone.required' => __('The Phone is required'),
                'address.required' => __('The Address is required'),
                'email.required' => __('The Email is required'),
                'city.required' => __('The City is required'),
                'term_condition.required' => __('Please agree with the Term and Condition')
            ]
        );

        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'message' => view('common.alert', ['type' => 'danger', 'message' => $validator->errors()->first()])->render()
            ]);
        }

        $user = Sentinel::findByCredentials([
            'login' => request()->get('email')
        ]);
        if ($user) {
            return $this->sendJson([
                'status' => 0,
                'message' => view('common.alert', ['type' => 'danger', 'message' => __('This user already exists')])->render()
            ]);
        }
        $password = createPassword(32);
        $credentials = [
            'email' => request()->get('email'),
            'password' => $password,
            'first_name' => request()->get('first_name', ''),
            'last_name' => request()->get('last_name', ''),
        ];

        try {
            $user = Sentinel::register($credentials);

            $args = [
                'mobile' => request()->get('phone'),
                'address' => request()->get('address'),
                'location' => request()->get('location', ''),
                'request' => 'request_a_partner',
            ];
            $user_model = new User();
            $user_model->updateUser($user->getUserId(), $args);

            update_user_meta($user->getUserId(), 'gender', request()->get('gender', 'mr'));
            update_user_meta($user->getUserId(), 'city', request()->get('city', ''));

            $zipcode = request()->get('zipcode', 0);
            update_user_meta($user->getUserId(), 'zipcode', null2empty($zipcode));

            $role = $user_model->getRoleByName('customer');
            $user_model->updateUserRole($user->getUserId(), $role->id);

        } catch (Exception $e) {
            return $this->sendJson([
                'status' => 0,
                'message' => view('common.alert', ['type' => 'danger', 'message' => $e->getMessage()])->render()
            ]);
        }

        if (!$user) {
            return $this->sendJson([
                'status' => 0,
                'message' => view('common.alert', ['type' => 'danger', 'message' => 'Can not register. Please try again!'])->render()
            ]);
        } else {
            do_action('hh_user_registered_as_partner', $user->getUserId(), $password);
            $return = [
                'user_id' => $user->getUserId(),
                'timestamp' => time()
            ];
            return $this->sendJson([
                'status' => 0,
                'force_redirect' => url('welcome-user', ['return' => base64_encode(json_encode($return))]),
            ]);
        }
    }

    public function _becomeAHost(Request $request)
    {
        if (!is_user_logged_in() && get_option('enable_partner_registration', 'on') == 'on') {
            return view('frontend.become-a-host');
        } else {
            return redirect('/');
        }
    }

    public function _updateUserItem(Request $request)
    {
        $user_id = request()->get('userID');
        $user_encrypt = request()->get('userEncrypt');
        if (!hh_compare_encrypt($user_id, $user_encrypt)) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }
        $user = get_user_by_id($user_id);
        if (!$user) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }
        $first_name = request()->get('user_first_name');
        $last_name = request()->get('user_last_name');
        $role_id = request()->get('user_role', 3);

        $password = trim(request()->get('user_password'));

        $validation = [
            'user_role' => 'required|numeric',
        ];

        if (!empty($password)) {
            $validation['user_password'] = 'required|min:6';
        }

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => $validator->errors()->first()
            ]);
        }
        if (!empty($password)) {

            $credentials = [
                'password' => $password,
            ];
            $user = Sentinel::update($user, $credentials);
        }

        if ($user) {
            $user_model = new User();
            $data = [
                'first_name' => $first_name,
                'last_name' => $last_name
            ];
            $user_model->updateUser($user->getUserId(), $data);

            $update = $user_model->updateUserRole($user_id, $role_id);

            if ($update) {
                return $this->sendJson([
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Successfully updated'),
                    'reload' => true
                ]);
            }
        }

        return $this->sendJson([
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Has error when updating')
        ]);
    }

    public function _getUserDeleteModal(Request $request)
    {
        $user_id = request()->get('userID');
        $user_encrypt = request()->get('userEncrypt');
        if (!hh_compare_encrypt($user_id, $user_encrypt)) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }
        $user = get_user_by_id($user_id);
        if (!$user) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }

        return $this->sendJson([
            'status' => 1,
            'html' => view('dashboard.screens.administrator.user-confirm-delete-form', ['user_id' => $user_id, 'user_encrypt' => $user_encrypt])->render()
        ]);
    }

    public function _getUserItem(Request $request)
    {
        $user_id = request()->get('userID');
        $user_encrypt = request()->get('userEncrypt');
        if (!hh_compare_encrypt($user_id, $user_encrypt)) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }
        $user = get_user_by_id($user_id);
        if (!$user) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }
        return $this->sendJson([
            'status' => 1,
            'html' => view('dashboard.components.user-edit-form', ['user' => $user])->render()
        ]);
    }

    public function _deleteUser(Request $request)
    {
        $userID = request()->get('userID');
        $userEncrypt = request()->get('userEncrypt');

        if (!hh_compare_encrypt($userID, $userEncrypt)) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }
        $user = Sentinel::findById($userID);
        if (!$user) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }

        $this->upateDataBeforeDelete($userID);
        $this->deleteRelatedData($userID);

        return $this->sendJson([
            'status' => 1,
            'title' => __('System Alert'),
            'message' => __('This member has been deleted'),
            'reload' => true
        ]);
    }

    public function _deleteUserModal(Request $request)
    {
        $userID = request()->get('userID');
        $userEncrypt = request()->get('userEncrypt');

        if (!hh_compare_encrypt($userID, $userEncrypt)) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }

        $user = Sentinel::findById($userID);
        if (!$user) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user does not exist')
            ]);
        }

        $this->upateDataBeforeDelete($userID);

        $type_delete = $request->post('delete_type', 'all');
        if ($type_delete == 'all') {
            $this->deleteRelatedData($userID, true);
        } elseif ($type_delete == 'assign') {
            $user_assign = $request->post('user_assign');
            $user_assign_object = Sentinel::findById($user_assign);
            if (!$user_assign_object) {
                return $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('This user assign does not exist')
                ]);
            }
            $this->deleteAndAssignRelatedData($userID, $user_assign);
        }

        return $this->sendJson([
            'status' => 1,
            'title' => __('System Alert'),
            'message' => __('This user has been deleted'),
            'reload' => true
        ]);
    }

    private function upateDataBeforeDelete($user_id)
    {
        $commentModel = new Comment();
        $listComment = $commentModel->getCommentsByUserID($user_id);
        $commentModel->deleteCommentsByUserID($user_id);
        if (!$listComment->isEmpty()) {
            foreach ($listComment as $item) {
                CommentController::get_inst()->updateRating($item->post_id, $item->post_type);
            }
        }
    }

    private function deleteRelatedData($user_id, $all = false)
    {
        $model = new User();
        $model->deleteRelatedData($user_id);
        if ($all) {
            $this->deleteServiceData($user_id);
        }
        $this->deleteUserMediaFolder($user_id);
    }

    private function deleteAndAssignRelatedData($user_id, $user_assign)
    {
        $model = new User();
        $model->deleteRelatedData($user_id, true);
        $this->assignServiceData($user_id, $user_assign);
    }

    function deleteServiceData($user_id)
    {
        HomeController::get_inst()->deleteDataByUserID($user_id);
        ExperienceController::get_inst()->deleteDataByUserID($user_id);
        CarController::get_inst()->deleteDataByUserID($user_id);
    }

    function assignServiceData($user_id, $user_assign)
    {
        HomeController::get_inst()->assignDataByUserID($user_id, $user_assign);
        ExperienceController::get_inst()->assignDataByUserID($user_id, $user_assign);
        CarController::get_inst()->assignDataByUserID($user_id, $user_assign);
        MediaController::get_inst()->assignDataByUserID($user_id, $user_assign);
    }

    private function deleteUserMediaFolder($user_id)
    {
        $rmFolderPath = $publicPath = storage_path('app/public/u-' . $user_id);
        if (is_dir($rmFolderPath)) {
            rmdir_recursive($rmFolderPath);
        }
    }

    public function _addNewUser(Request $request)
    {
        $email = request()->get('user_email');
        $first_name = request()->get('user_first_name');
        $last_name = request()->get('user_last_name');
        $role = request()->get('user_role', 3);
        $password = request()->get('user_password');

        $validator = Validator::make($request->all(), [
            'user_email' => 'required|email',
            'user_role' => 'required|numeric',
            'user_password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => $validator->errors()->first()
            ]);
        }

        $user_exist = $user = Sentinel::findByCredentials([
            'login' => $email
        ]);

        if ($user_exist) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This email has been registered')
            ]);
        }
        $user_model = new User();
        $role = Sentinel::findRoleById($role);
        $user_data = [
            'email' => $email,
            'password' => $password,
        ];

        $user = Sentinel::registerAndActivate($user_data);

        if (!$user) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Can not create user')
            ]);
        }
        $user->roles()->attach($role);

        $data = [
            'first_name' => $first_name,
            'last_name' => $last_name
        ];
        $user_model->updateUser($user->getUserId(), $data);

        update_user_meta($user->getUserId(), 'last_check_notification', time());

        do_action('hh_registered_user', $user->getUserId(), $password);

        return $this->sendJson([
            'status' => 1,
            'title' => __('System Alert'),
            'message' => __('Successfully created new user'),
            'reload' => true
        ]);
    }

    public function _addCreateUserButton()
    {
        $screen = current_screen();
        if ($screen == 'user-management') {
            echo view('dashboard.components.quick-add-user')->render();
        }
    }

    public function _userRegistration(Request $request, $page = 1)
    {
        $user_model = new User();
        $data = [
            'search' => request()->get('_s'),
            'page' => $page,
            'orderby' => request()->get('orderby', 'id'),
            'order' => request()->get('order', 'desc'),
        ];
        $allUsers = $user_model->allPartnerRequest($data);
        $folder = $this->getFolder();
        return view("dashboard.screens.{$folder}.partner-registration", ['bodyClass' => 'hh-dashboard', 'allUsers' => $allUsers]);
    }

    public function _userManagement(Request $request, $page = 1)
    {
        $user_model = new User();
        $data = [
            'search' => request()->get('_s'),
            'page' => $page,
            'orderby' => request()->get('orderby', 'id'),
            'order' => request()->get('order', 'desc'),
            'role' => request()->get('role', ''),
        ];
        $allUsers = $user_model->allUsers($data);
        $folder = $this->getFolder();
        return view("dashboard.screens.{$folder}.user-management", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'allUsers' => $allUsers]);
    }

}

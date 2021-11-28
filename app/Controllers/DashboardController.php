<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Sentinel;

class DashboardController extends Controller
{
    public function _getQRcode(Request $request)
    {
        $service_id = request()->get('serviceID');
        $service_encrypt = request()->get('serviceEncrypt');
        $service_type = request()->get('serviceType', 'home');

        if (!hh_compare_encrypt($service_id, $service_encrypt)) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This service does not exist')
            ]);
        }

        $serviceObject = get_post($service_id, $service_type);
        if (!is_object($serviceObject)) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This service does not exist')
            ]);
        }

        $service_url = get_the_permalink($serviceObject->post_id, $serviceObject->post_slug, $serviceObject->post_type);
        start_get_view();
        ?>
        <div class="qrcode-render">
            <?php
            echo QrCode::size(200)->generate($service_url);
            ?>
        </div>
        <?php
        $qrcode_html = end_get_view();
        return $this->sendJson([
            'status' => 1,
            'title' => __('System Alert'),
            'message' => __('Successfully get QR Code'),
            'html' => $qrcode_html
        ]);
    }

    public function _updateYourPayoutInformation(Request $request)
    {
        $user_id = request()->get('user_id');
        $user_encrypt = request()->get('user_encrypt');
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
        $payout_payment = request()->get('payout_payment');
        $payout_detail = request()->get('payout_detail');

        update_user_meta($user_id, 'payout_payment', $payout_payment);
        update_user_meta($user_id, 'payout_detail', $payout_detail);

        return $this->sendJson([
            'status' => 1,
            'title' => __('System Alert'),
            'message' => __('Updated payout information successfully')
        ]);
    }

    public function _updatePassword(Request $request)
    {
        $user_id = request()->get('user_id');
        $user_encrypt = request()->get('user_encrypt');
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

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => $validator->errors()->first()
            ]);
        } else {
            $password = trim(request()->get('password'));
            $credentials = [
                'password' => $password,
            ];
            $user_updated = Sentinel::update($user, $credentials);
            return $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Updated password successfully')
            ]);
        }
    }

    public function _getFontIcon(Request $request)
    {
        global $text;
        $text = request()->get('text', '');
        $text = strtolower(trim($text));
        if (empty($text)) {
            $this->sendJson(
                [
                    'status' => 0,
                    'data' => __('Not found icons')
                ]
                , true);
        }
        include public_path('fonts/fonts.php');
        include public_path('fonts/fonts-system.php');
        if (!isset($fonts) && !isset($fonts_system)) {
            $this->sendJson([
                'status' => 0,
                'data' => __('Not found icons data')
            ], true);
        }
	    $fonts_merge = [];
	    if (isset($fonts)) {
		    $fonts_merge = $fonts;
	    }
	    if (isset($fonts_system)) {
		    $fonts_merge = array_merge($fonts_merge, $fonts_system);
	    }
        $results = array_filter($fonts_merge, function ($key) {
            global $text;
            if (strpos(strtolower($key), $text) === false) {
                return false;
            } else {
                return true;
            }
        }, ARRAY_FILTER_USE_KEY);
        if (empty($results)) {
            $this->sendJson([
                'status' => 0,
                'data' => __('Not found icons')
            ], true);
        } else {
            $this->sendJson([
                'status' => 1,
                'data' => $results
            ], true);
        }
    }

    public function _updateYourAvatar(Request $request)
    {
        $user_id = request()->get('user_id');
        $user_encrypt = request()->get('user_encrypt');
        $avatar = request()->get('avatar');
        if (hh_compare_encrypt($user_id, $user_encrypt) && $user_id == get_current_user_id()) {
            $user_model = new User();
            $updated = $user_model->updateUser($user_id, ['avatar' => $avatar]);
            if (!is_null($updated)) {
                return $this->sendJson([
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Updated successfully')
                ]);
            } else {
                return $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Can not update this user. Try again!')
                ]);
            }
        } else {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user is invalid')
            ]);
        }
    }

    public function _updateYourProfile(Request $request)
    {
        $user_id = request()->get('user_id');
        $user_encrypt = request()->get('user_encrypt');
        $first_name = request()->get('first_name');
        $last_name = request()->get('last_name');
        $mobile = request()->get('mobile');
        $location = request()->get('location');
        $address = request()->get('address');
        $description = request()->get('description');
        $video = request()->get('video');

        if (hh_compare_encrypt($user_id, $user_encrypt) && $user_id == get_current_user_id()) {
            $args = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'mobile' => $mobile,
                'location' => $location,
                'address' => $address,
                'description' => $description,
                'video' => $video,
            ];
            $user = get_user_by_id($user_id);

            if (is_admin($user_id)) {
                $email = request()->get('email');
                $check_user = get_user_by_email($email);
                if ($user->email != $email && (empty($email) || !is_email($email) || $check_user)) {
                    return $this->sendJson([
                        'status' => 0,
                        'title' => __('System Alert'),
                        'message' => __('Can not use this email')
                    ]);
                }
                $args['email'] = $email;
            }

            $user_model = new User();
            $updated = $user_model->updateUser($user_id, $args);
            if (!is_null($updated)) {
                return $this->sendJson([
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Updated successfully')
                ]);
            } else {
                return $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Can not update this user. Try again!')
                ]);
            }
        } else {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This user is invalid')
            ]);
        }
    }

    public function _getProfile()
    {
        $folder = $this->getFolder();
        return view("dashboard.screens.{$folder}.profile", ['role' => $folder, 'bodyClass' => 'hh-dashboard']);
    }

    public function index()
    {
        $folder = $this->getFolder();
        return view("dashboard.screens.{$folder}.dashboard", ['role' => $folder, 'bodyClass' => 'hh-dashboard']);
    }

}

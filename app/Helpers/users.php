<?php

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Mockery\Exception;

function get_activation_code($user_id)
{
    $user = new \App\Models\User();

    return $user->getActivationCode($user_id);
}

function createEmail($name = '')
{
    return $name . createPassword(4) . apply_filters('awebooking_create_email_domain', '@aweboking.org');
}

function createPassword($length = 8)
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

function get_all_roles()
{
    $user_model = new \App\Models\User();
    $users = $user_model->getAllRoles();
    if ($users) {
        $data = [];
        foreach ($users as $user) {
            $data[$user->id] = $user->name;
        }

        return $data;
    }
    return [];
}

function get_user_role($user_id, $type = '')
{
    $user_model = new \App\Models\User();
    $result = $user_model->getUserRole($user_id);
    if (!empty($type)) {
        return isset($result->$type) ? $result->$type : '';
    }
    return $result;
}

function user_can_edit_service($service_object, $user_id = null): bool
{
    if (is_null($user_id)) {
        $user_id = get_current_user_id();
    }
    if (!is_object($service_object)) {
        return false;
    }
    if (is_admin($user_id) || ((int)$user_id === (int)$service_object->author)) {
        return true;
    }
    return false;
}

function user_can_edit_post($post_object = null, $user_id = null): bool
{
    if (is_null($user_id)) {
        $user_id = get_current_user_id();
    }

    if (is_admin($user_id)) {
        return true;
    }

    return false;
}

function user_can_manage_post($user_id = null)
{
    if (is_null($user_id)) {
        $user_id = get_current_user_id();
    }

    if (is_admin($user_id)) {
        return true;
    }

    return false;
}


function logout_url($redirect = '')
{
    if (!$redirect) {
        $redirect = url('/');
    }
    return auth_url('logout') . '/?redirect_url=' . $redirect;
}

function is_user_logged_in()
{
    $userdata = get_current_user_data();
    return !empty($userdata) ? true : false;
}

function get_username($user_id)
{
    $user = get_user_by_id($user_id);
    if ($user) {
        if (!empty($user->first_name) || !empty($user->last_name)) {
            return $user->first_name . ' ' . $user->last_name;
        } else {
            return $user->email;
        }
    }
    return '';
}

function get_user_avatar($user_id = null, $size = [50, 50])
{
    if (is_null($user_id)) {
        $user = get_current_user_data();
    } else {
        $user = get_user_by_id($user_id);
    }
    if (!empty($user)) {
        $avatar_id = $user->avatar;
    } else {
        $avatar_id = 0;
    }
    $avatar_url = get_attachment_url($avatar_id, $size);

    return $avatar_url;
}

function get_current_user_id()
{
    $user_data = get_current_user_data();

    if ($user_data != null) {
        return $user_data->getUserId();
    } else {
        return 0;
    }
}

function is_admin($user_id = '')
{
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    $user_data = get_user_by_id($user_id);
    if ($user_data) {
        if($user_data->inRole('administrator') || $user_data->inRole('superadmin')){
            return true;
        }else {
            return false;
        }
        // return $user_data->inRole('administrator') ? true : false;
    }
    return false;
}

function is_partner($user_id = '')
{
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    $user_data = get_user_by_id($user_id);

    if ($user_data) {
        return $user_data->inRole('partner') ? true : false;
    }
    return false;
}

function is_customer($user_id = '')
{
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    $user_data = get_user_by_id($user_id);

    if ($user_data) {
        return $user_data->inRole('customer') ? true : false;
    }
    return false;
}

function get_current_user_data()
{
    return Sentinel::getUser();
}

function get_user_by_id($user_id)
{
    $user = Sentinel::findById($user_id);
    return (is_object($user)) ? $user : false;
}

function get_user_by_email($user_email)
{
    $credentials = [
        'login' => $user_email,
    ];

    $user = Sentinel::findByCredentials($credentials);
    return (is_object($user)) ? $user : false;
}

function get_admin_user()
{
    $admin_id = get_option('user_admin');
    return get_user_by_id($admin_id);
}

function get_users_by_role($role = 'administrator', $for_option = false)
{
    $return = [];
    $users = Sentinel::getUserRepository()->get();
    if (!empty($users) && is_object($users)) {
        foreach ($users as $user) {
            if ($user->inRole($role)) {
                if ($for_option) {
                    $return[$user->getUserId()] = '(' . $user->getUserId() . ') ' . get_username($user->getUserId());
                } else {
                    $return[$user->getUserId()] = get_username($user->getUserId());
                }
            }
        }
    }

    return $return;
}

function get_users_in_role($roles = ['administrator'], $exclude = '')
{
    $return = [];
    $users = Sentinel::getUserRepository()->get();
    if (!empty($users) && is_object($users)) {
        foreach ($users as $user) {
            $user_id = $user->getUserId();
            if (in_array($user->roles[0]['slug'], $roles) && $user_id != $exclude) {
                if (empty($user->first_name) && empty($user->last_name)) {
                    $return[$user_id] = trim(get_username($user_id));
                } else {
                    $return[$user_id] = trim(get_username($user_id)) . ' (' . $user->email . ')';
                }
            }
        }
    }
    return $return;
}

function get_user_meta($user_id, $meta_key, $default = '')
{
    $user_model = new \App\Models\User();

    $result = $user_model->getUserMeta($user_id, $meta_key);
    if (!empty($result) && is_object($result)) {
        return maybe_unserialize($result->meta_value);
    } else {
        return $default;
    }
}

function get_all_user_list()
{
    $user_model = new \App\Models\User();
    $result = $user_model->getAllUserList();
    return $result;
}

function update_user_meta($user_id, $meta_key, $meta_value = '')
{
    $user_model = new \App\Models\User();

    return $user_model->updateUserMeta($user_id, $meta_key, $meta_value);
}

function remove_user_meta($user_id, $meta_key)
{
	$user_model = new \App\Models\User();

	return $user_model->deleteUserMetaByWhere([
		'user_id' => $user_id,
		'meta_key' => $meta_key
	]);
}

function create_new_user($data = [])
{
    $default = [
        'email' => '',
        'password' => \Illuminate\Support\Str::random(12),
        'first_name' => '',
        'last_name' => '',
        'role' => 'customer',
    ];

    $data = wp_parse_args($data, $default);

    if (empty($data['email']) || !is_email($data['email'])) {
        return [
            'status' => 0,
            'message' => __('Invalid Email address')
        ];
    }
    if (empty($data['password']) || strlen($data['password']) < 6) {
        return [
            'status' => 0,
            'message' => __('The password has at least 6 characters')
        ];
    }

    $user = Sentinel::findByCredentials([
        'login' => $data['email']
    ]);

    if ($user) {
        return [
            'status' => 0,
            'message' => __('This user already exists')
        ];
    } else {
        try {
            $approval = get_option('account_approval', 'off');

            if ($approval == 'on') {
                $user = Sentinel::register($data);
            } else {
                $user = Sentinel::registerAndActivate($data);
            }

        } catch (Exception $e) {
            return [
                'status' => 0,
                'message' => $e->getMessage()
            ];
        }

        if (!$user) {
            return [
                'status' => 0,
                'message' => __('Can not create new user')
            ];
        } else {

            $user_model = new \App\Models\User();
            $role = $user_model->getRoleByName($data['role']);
            $user_model->updateUserRole($user->getUserId(), $role->id);
            if ($approval == 'on') {
                $user_model->updateUser($user->getUserId(), ['request' => 'request_a_customer']);
                do_action('hh_user_registered_as_customer', $user->getUserId());
            }
            do_action('hh_registered_user', $user->getUserId(), $data['password'], $approval);

            return [
                'status' => 1,
                'message' => __('Registered successfully'),
                'user' => $user,
                'password' => $data['password']
            ];
        }
    }
}

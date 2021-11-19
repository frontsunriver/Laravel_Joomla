<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class APIController extends Controller
{

    public function _index(Request $request)
    {
        $folder = $this->getFolder();
        $user_id = get_current_user_id();
        $default_access_token = get_user_meta($user_id, 'access_token');
        if (empty($default_access_token)) {
            $default_access_token = create_api_token(get_current_user_id());
            update_user_meta($user_id, 'access_token', $default_access_token);
        }

        return view("dashboard.screens.{$folder}.api", ['bodyClass' => 'hh-dashboard']);
    }

    public function _saveApiSettings(Request $request)
    {
        $api_lifetime = request()->get('api_lifetime', 30);
        $api_lifetime_type = request()->get('api_lifetime_type', 'day');

        update_opt('api_lifetime', $api_lifetime);
        update_opt('api_lifetime_type', $api_lifetime_type);

        return $this->sendJson([
            'status' => 1,
            'title' => __('System Alert'),
            'message' => __('Successfully Updated')
        ]);
    }

}

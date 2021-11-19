<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sentinel;

class SocialController extends Controller
{
    public function _checkLogin(Request $request, $social = '')
    {
        if (!empty($social)) {
            $return = [
                'status' => false,
                'message' => __('Some problem occurred, please try again.')
            ];
            switch ($social) {
                case 'facebook':
                    $return = \FacebookLogin::get_inst()->checkLogin();
                    break;
                case 'google':
                    $return = \GoogleLogin::get_inst()->checkLogin();
                    break;
            }

            if ($return['status']) {
                Sentinel::login($return['user']);
                return redirect()->route('home-page');
            } else {
                echo balanceTags($return['message']);
                exit;
            }
        } else {
            return response()->redirectTo('/');
        }
    }
}

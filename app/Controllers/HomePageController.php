<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Home;

class HomePageController extends Controller
{

    public function _comingSoon(Request $request){
        return view('errors.503');
    }
    public function _contactUsPost(Request $request)
    {
        $title = esc_html(request()->get('title'));
        $email = esc_html(request()->get('email'));
        $message = balanceTags(request()->get('message'));

        if (!is_email($email)) {
            return $this->sendJson([
                'status' => 0,
                'message' => view('common.alert', ['type' => 'danger', 'message' => __('The email is incorrect')])->render()
            ]);
        }
        if (empty($title)) {
            return $this->sendJson([
                'status' => 0,
                'message' => view('common.alert', ['type' => 'danger', 'message' => __('Please enter the title')])->render()
            ]);
        }
        if (get_option('use_google_captcha', 'off') == 'on') {
            $recaptcha = new \ReCaptcha\ReCaptcha(get_option('google_captcha_secret_key'));
            $gRecaptchaResponse = request()->get('g-recaptcha-response');
            $resp = $recaptcha->verify($gRecaptchaResponse, $request->ip());
            if (!$resp->isSuccess()) {
                return $this->sendJson([
                    'status' => 0,
                    'message' => view('common.alert', ['type' => 'danger', 'message' => __('Your request was denied')])->render()
                ]);
            }
        }

        $admin_data = get_admin_user();

        $subject = sprintf(__('[%s] Has new message from %s'), get_option('site_name'), $email);

        send_mail($email, $title, $admin_data->email, $subject, $message);

        return $this->sendJson([
            'status' => 1,
            'message' => view('common.alert', ['type' => 'success', 'message' => __('Successfully sent')])->render()
        ]);
    }

    public function _contactPage()
    {
        return view('frontend.contact-us');
    }

    public function index()
    {
        return view('frontend.homepage.default');
    }

    public function _homePage()
    {
        return view('frontend.homepage.home-demo');
    }

    public function _experiencePage()
    {
        return view('frontend.homepage.experience-demo');
    }

    public function _carPage()
    {
        return view('frontend.homepage.car-demo');
    }

    public function searchCities(Request $request) {
        $home = new Home();
        $query = request()->get('query');
        $result = $home->searchCitiesList($query);
        $cities = array();
        foreach ($result as $value) {
            $cities[] = get_translate($value->location_city);
        }
        echo json_encode($cities);
    }
}

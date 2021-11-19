<?php

use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class GoogleLogin
{
    public $path;
    private $gClient;
    private $google_oauthV2;

    public function __construct()
    {
    }

    public function config()
    {
        $clientId = get_option('google_client_id');
        $clientSecret = get_option('google_client_secret');
        $this->gClient = new Google_Client();
        $this->gClient->setApplicationName('Login to ' . url('/'));
        $this->gClient->setClientId($clientId);
        $this->gClient->setClientSecret($clientSecret);
        $this->gClient->setRedirectUri(url('social-login/google'));
        $this->gClient->addScope([
            'profile',
            'email'
        ]);

        $this->google_oauthV2 = new Google_Service_Oauth2($this->gClient);
    }

    public function getLoginUrl()
    {
        if (social_enable('google')) {
            $this->config();

            return $this->gClient->createAuthUrl();
        } else {
            return '';
        }

    }

    public function checkLogin()
    {
        if (isset($_GET['code']) && social_enable('google')) {
            $this->config();

            $this->gClient->authenticate($_GET['code']);
            $token = $this->gClient->getAccessToken();
            $this->gClient->setAccessToken($token);

            if ($this->gClient->getAccessToken()) {
                $gpUserProfile = $this->google_oauthV2->userinfo->get();
                $googleid = $gpUserProfile['id'];
                $email = $gpUserProfile['email'];
                if (empty($email)) {
                    $email = createEmail($googleid);
                }
                $first_name = $gpUserProfile['givenName'];
                $last_name = $gpUserProfile['familyName'];

                $user = get_user_by_email($email);
                if (!$user) {
                    $password = createPassword(32);
                    $credentials = [
                        'email' => $email,
                        'password' => $password,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                    ];
                    $user = Sentinel::registerAndActivate($credentials);

                    $content = view('frontend.email.welcome-user', ['user' => $user, 'password' => $password])->render();
                    $admin = get_admin_user();
                    $subject = sprintf(__("[%s] You have registered a new account"), get_option('site_name'));
                    send_mail($admin->email, '', $user->email, $subject, $content);
                }
                if ($user) {
                    return [
                        'status' => 1,
                        'user' => $user
                    ];
                } else {
                    return [
                        'status' => 0,
                        'message' => 'Some problem occurred, please try again.'
                    ];
                }

            } else {
                return [
                    'status' => 0,
                    'message' => 'Some problem occurred, please try again.'
                ];
            }
        } else {
            return [
                'status' => 0,
                'message' => 'Some problem occurred, please try again.'
            ];
        }
    }

    public static function get_inst()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}

GoogleLogin::get_inst();

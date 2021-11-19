<?php

use Cartalyst\Sentinel\Native\Facades\Sentinel;

class FacebookLogin
{
    public $path;
    private $fb;
    private $helper;
    private $accessToken;

    public function __construct()
    {

    }

    public function config()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->fb = new \Facebook\Facebook([
            'app_id' => get_option('facebook_api'),
            'app_secret' => get_option('facebook_secret'),
            'default_graph_version' => 'v2.10',
        ]);

        $this->helper = $this->fb->getRedirectLoginHelper();

        try {
            if (isset($_SESSION['facebook_access_token'])) {
                $this->accessToken = $_SESSION['facebook_access_token'];
            } else {
                $this->accessToken = $this->helper->getAccessToken();
            }
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    public function getLoginUrl()
    {
        if (social_enable('facebook')) {
            $this->config();
            $permissions = ['email'];
            $loginUrl = $this->helper->getLoginUrl(url('/social-login/facebook'), $permissions);

            return $loginUrl;
        } else {
            return '';
        }
    }

    public function checkLogin()
    {
        if (social_enable('facebook')) {
            $this->config();
            if (!isset($this->accessToken)) {
                if ($this->helper->getError()) {
                    return [
                        'status' => 0,
                        'message' => "Error: " . $this->helper->getError() . "\n"
                    ];
                } else {
                    return [
                        'status' => 0,
                        'message' => 'Bad request'
                    ];
                }
            }

            if (isset($_SESSION['facebook_access_token'])) {
                $this->fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
            } else {
                $_SESSION['facebook_access_token'] = (string)$this->accessToken;
                $oAuth2Client = $this->fb->getOAuth2Client();
                $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
                $_SESSION['facebook_access_token'] = (string)$longLivedAccessToken;
                $this->fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
            }
            try {
                $profile_request = $this->fb->get('/me?fields=id,name,first_name,last_name,email');
                $profile = $profile_request->getGraphNode()->asArray();
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                return [
                    'status' => 0,
                    'message' => 'Graph returned an error: ' . $e->getMessage()
                ];
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                return [
                    'status' => 0,
                    'message' => 'Facebook SDK returned an error: ' . $e->getMessage()
                ];
            }

            $fbid = $profile['id'];
            $email = isset($profile['email']) ? $profile['email']: createEmail($fbid);
            $first_name = $profile['first_name'];
            $last_name = $profile['last_name'];

            $user = get_user_by_email($email);

            if (!$user) {
                $user_model = new \App\Models\User();
                $password = createPassword(32);
                $credentials = [
                    'email' => $email,
                    'password' => $password,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                ];
                $user = Sentinel::registerAndActivate($credentials);
                $role = $user_model->getRoleByName('customer');
                $user_model->updateUserRole($user->getUserId(), $role->id);

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
                'message' => 'Facebook login is not supported'
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

FacebookLogin::get_inst();

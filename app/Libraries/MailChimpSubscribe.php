<?php

class MailChimpSubscribe
{
    protected $mail_chimp;
    protected $key;
    protected $list_id = '';

    public function init()
    {
        $this->key = get_option('mailchimp_api_key');
        $this->list_id = get_option('mailchimp_list');
    }

    public function addNewSubscriber($mail = '')
    {
        $this->init();
        $data_center = substr($this->key, strpos($this->key, '-') + 1);
        $url = 'https://' . $data_center . '.api.mailchimp.com/3.0/lists/' . $this->list_id . '/members/';

        $json = json_encode([
            'email_address' => $mail,
            'status' => 'subscribed',
            'merge_fields' => [
                'FNAME' => '',
                'LNAME' => ''
            ]
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization: apikey ' . $this->key,
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => $json
        ));
        $response = curl_exec($ch);
        $response = json_decode($response);
        if (!empty($response)) {
            if ($response->status == 400) {
                return [
                    'status' => 0,
                    'title' => 'Alert System',
                    'message' => '<div class="text text-danger">' . $response->title . '</div>'
                ];
            } elseif ($response->status == 'subscribed') {
                return [
                    'status' => 1,
                    'title' => 'Alert System',
                    'message' => '<div class="text text-success">'. __('Subscribe successfully. Thanks for registering for our email list') .'</div>',
                ];
            } else {
                return [
                    'status' => 0,
                    'title' => 'Alert System',
                    'message' => '<div class="text text-danger">'. __('Have an error when submit this form') .'</div>'
                ];
            }
        } else {
            return [
                'status' => 0,
                'title' => 'Alert System',
                'message' => '<div class="text text-danger">'. __('Have an error when submit this form') .'</div>'
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

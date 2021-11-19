<?php

use App\Abstracts\Gateways;

class BankTransfer extends Gateways
{
    static $paymentId = 'bank_transfer';

    protected $params;

    public function __construct()
    {
        parent::__construct();
    }

    public static function getID()
    {
        return self::$paymentId;
    }

    public static function getName()
    {
        return __('Bank Transfer');
    }

    public static function getHtml()
    {
        return '';
    }

    public static function enable()
    {
        // TODO: Implement enable() method.
        $enable = get_option('enable_' . self::$paymentId, 'off');
        return !!($enable == 'on');
    }

    public static function getLogo()
    {
        $img = get_option(self::$paymentId . '_logo');
        if(!$img){
            return asset('/images/bank-transfer.png');
        }
        return get_attachment_url($img, 'full');
    }

    public static function getDescription()
    {
        $desc = get_option(self::$paymentId . '_description');
        return $desc;
    }

    public static function getOptions()
    {
        return [
            'title' => [
                'id' => 'sub_tab_' . self::$paymentId,
                'label' => self::getName()
            ],
            'content' => [
                [
                    'id' => 'enable_' . self::$paymentId,
                    'label' => __('Enable'),
                    'type' => 'on_off',
                    'std' => 'on',
                    'section' => 'sub_tab_' . self::$paymentId
                ],
                [
                    'id' => self::$paymentId . '_logo',
                    'label' => __('Logo'),
                    'type' => 'upload',
                    'translation' => false,
                    'section' => 'sub_tab_' . self::$paymentId
                ],
                [
                    'id' => self::$paymentId . '_description',
                    'label' => __('Description'),
                    'type' => 'textarea',
	                'translation' => true,
                    'section' => 'sub_tab_' . self::$paymentId
                ],
            ]
        ];
    }


    public function setDefaultParams()
    {
        // TODO: Implement setDefaultParams() method.
    }

    public function setParams($params = [])
    {
        // TODO: Implement setParams() method.
        $default = [];
        $params = wp_parse_args($params, $default);
        $this->params = $params;
    }

    public function validation()
    {
        // TODO: Implement validation() method.
        return true;
    }

    public function purchase($booking_id = false, $params = [])
    {
        $this->setOrderObject($booking_id);
        $this->setParams($params);
        do_action('hh_purchase_' . self::$paymentId, $this->params);
        return [
            'status' => 'incomplete',
            'redirectUrl' => $this->successUrl(),
            'message' => __('The system is redirecting')
        ];
    }

    public function completePurchase($booking_id = false, $params = [])
    {
        do_action('hh_complete_purchase_' . self::$paymentId, $this->params);
        return [
            'status' => 'incomplete'
        ];
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

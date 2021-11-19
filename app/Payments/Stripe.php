<?php

use App\Abstracts\Gateways;

class Stripe extends Gateways
{
    static $paymentId = 'stripe';

    public function __construct()
    {
        parent::__construct();
        add_action('hh_register_scripts', [$this, '_enqueues'], 20);
        add_action('init_frontend_header', [$this, '_renderParams']);
    }

    public function _renderParams()
    {
        if (!self::enable()) {
            return false;
        }
        ?>
        <script>
            var hh_stripe = {
                publish_key: '<?php echo get_option(self::$paymentId . '_publishable_key') ?>'
            }
        </script>
        <?php
    }

    public function _enqueues($enqueue)
    {
        if (!self::enable()) {
            return false;
        }
        $enqueue->addScript('stripe-js', 'https://js.stripe.com/v3/', false, false, 'frontend', true);
        $enqueue->addScript('stripe-custom-js', asset('js/stripe.js'));
    }

    public static function enable()
    {
        // TODO: Implement enable() method.
        $enable = get_option('enable_' . self::$paymentId, 'off');
        return !!($enable == 'on');
    }

    public static function getHtml()
    {
        if (!self::enable()) {
            return false;
        }

        start_get_view();
        enqueue_script('stripe-js');
        enqueue_script('stripe-custom-js');
        ?>
        <div>
            <div class="form-group">
                <div id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                </div>
                <!-- Used to display form errors. -->
                <div id="card-errors" role="alert"></div>
            </div>
            <input type="hidden" name="<?php echo self::$paymentId; ?>_token" value="">
        </div>
        <?php
        return end_get_view();
    }

    public static function getID()
    {
        return self::$paymentId;
    }

    public static function getName()
    {
        return __('Stripe');
    }

    public static function getLogo()
    {
        $img = get_option(self::$paymentId . '_logo');
        if (!$img) {
            return asset('/images/stripe.png');
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
                    'std' => 'off',
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
                    'id' => self::$paymentId . '_publishable_key',
                    'label' => __('Publishable Key'),
                    'type' => 'text',
                    'layout' => 'col-12 col-sm-6',
                    'translation' => false,
                    'section' => 'sub_tab_' . self::$paymentId
                ],
                [
                    'id' => self::$paymentId . '_secret_key',
                    'label' => __('Secret Key'),
                    'type' => 'text',
                    'layout' => 'col-12 col-sm-6',
                    'translation' => false,
                    'break' => true,
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

    public function setParams($params)
    {

        $default = [
            'source' => request()->get('stripe_token'),
            'total' => $this->convertPrice($this->orderObject->total, $this->orderObject->currency),
            'currency' => $this->getCurrencyUnit($this->orderObject->currency),
            'email' => $this->orderObject->email,
            'name' => $this->orderObject->first_name . ' ' . $this->orderObject->last_name,
            'phone' => $this->orderObject->phone
        ];

        $this->params = wp_parse_args($params, $default);
    }

    public function setDefaultParams()
    {
        \Stripe\Stripe::setApiKey(get_option(self::$paymentId . '_secret_key'));
    }

    public function validation()
    {
        return true;
    }

    public function purchase($orderID = false, $params = [])
    {
        $this->setOrderObject($orderID);

        $this->setDefaultParams();
        $this->setParams($params);

        do_action('hh_purchase_' . self::$paymentId, $this->params);

        try {
            $customer = \Stripe\Customer::create([
                'source' => $this->params['source'],
                'email' => $this->params['email'],
                'name' => $this->params['name'],
                'phone' => $this->params['phone']
            ]);
            $charge = \Stripe\Charge::create(
                [
                    'amount' => $this->params['total'],
                    'currency' => $this->params['currency'],
                    'customer' => $customer->id,
                ]
            );
            $captured = (isset($charge->captured) && $charge->captured) ? 'yes' : 'no';
            if ('yes' === $captured) {
                if ('pending' === $charge->status) {
                    return [
                        'status' => 'incomplete',
                        'redirectUrl' => $this->successUrl(),
                        'message' => __('The booking has not been completed')
                    ];
                }

                if ('succeeded' === $charge->status) {
                    return [
                        'status' => 'completed',
                        'redirectUrl' => $this->successUrl(),
                        'message' => __('Successful booking')
                    ];
                }

                if ('failed' === $charge->status) {
                    return [
                        'status' => 'cancelled',
                        'redirectUrl' => $this->cancelUrl(),
                        'message' => __('The booking has been canceled')
                    ];
                }
            } else {
                return [
                    'status' => 'pending',
                    'message' => __('Can not create booking. Try again!')
                ];
            }
        } catch (Exception $ex) {
            return [
                'status' => 'pending',
                'message' => sprintf(__('Have error when processing: Code %s - Message %s'), $ex->getCode(), $ex->getMessage())
            ];
        }
    }

    public function completePurchase($orderID = false, $params = [])
    {
        return true;
    }

    protected function convertPrice($price, $currency)
    {
        $currency = maybe_unserialize($currency);
        $price = (float)$price * (float)$currency['exchange'];
        $price = round($price, 2);
        $price *= 100;
        return $price;
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


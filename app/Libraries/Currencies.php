<?php
use  Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class Currencies
{
    private $sessionCurrency = 'hh_currency';

    public function __construct()
    {
        add_action('init', [$this, '_setDefaultCurrency']);
    }

    public function convertPrice($price, $show_symbol = true, $format = true, $_currency = null)
    {
        $currency_list = list_currencies();
        $currency = $this->currentCurrency();
        if ($currency) {
            $currency = isset($currency['unit']) ? $currency['unit'] : '';
        }
        if (!empty($_currency)) {
            $currency = $_currency['unit'];
        }

        $rate = 1;
        $symbol = '';
        $currency_position = 'right';
        $decimal = 2;
        $thousand_separator = '.';
        $decimal_separator = ',';

        if (!empty($currency_list) && $currency) {
            foreach ($currency_list as $item) {
                $unit = trim($item['unit']);
                if ($currency == $unit) {
                    // $rate = (float)$item['exchange'];
                    // $currency_position = $item['position'];
                    // $symbol = $item['symbol'];
                    // $thousand_separator = $item['thousand_separator'];
                    // $decimal_separator = $item['decimal_separator'];
                    // $decimal = (int)$item['currency_decimal'];
                    $rate = 1;
                    $currency_position = 'right';
                    $symbol = ' €';
                    $decimal = (int)$item['currency_decimal'];
                    $thousand_separator = '.';
                    $decimal_separator = ',';
                    break;
                }
            }
        }

        $price = (float)$price * $rate;

        $price = round($price, $decimal);

        if ($format) {
            $price = number_format($price, $decimal, $decimal_separator, $thousand_separator);
        }
        if (!$show_symbol) {
            return $price;
        } else {
            if ($currency_position == 'right') {
                return $price . $symbol;
            } else {
                return $symbol . $price;
            }
        }
    }

    public function _setDefaultCurrency()
    {
        $get_currency = request()->get('currency', '');
        if(!empty($get_currency)) {
            $all_currencies = list_currencies();
            if(!empty($all_currencies)){
                foreach ($all_currencies as $key => $val){
                    if($get_currency == trim($val['unit'])){
                        $this->_setCurrency($val);
                        break;
                    }
                }
            }
        }else{
            if (!$this->currentCurrency()) {
                $primaryCurrency = $this->primaryCurrency();
                $this->_setCurrency($primaryCurrency);
            }
        }
    }

    private function _setCurrency($currency)
    {
        if ($currency) {
            Session::put($this->sessionCurrency, $currency);
        } else {
            Session::put($this->sessionCurrency, [
                'name' => 'USD',
                'unit' => 'USD',
                'symbol' => '$',
                'exchange' => '1',
                'position' => 'left',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'currency_decimal' => '2'
            ]);
        }
    }

    public function currentCurrency($key = '')
    {
        $currency = Session::get($this->sessionCurrency);
        
	    if(is_null($currency)){
		    $currency = $this->primaryCurrency();
	    }

        $currency['symbol'] = " €";
        $currency['position'] = "right";

        if ($key) {
            return isset($currency[$key]) ? $currency[$key] : false;
        }

        return $currency;
    }

    public function primaryCurrency()
    {
        $base_currency = get_option('primary_currency', []);
        if (empty($base_currency)) {
            return false;
        }
        $list_currency = list_currencies();
        if (!empty($list_currency)) {
            foreach ($list_currency as $key => $item) {
                if ($base_currency === trim($item['unit'])) {
                    $base_currency = $item;
                    return $base_currency;
                }
            }
        }
        if (!empty($base_currency)) {
            return $base_currency;
        }

        return false;
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

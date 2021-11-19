<?php

    namespace App\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\Coupon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Config;
    use Sentinel;


    class CouponController extends Controller
    {

        public function __construct()
        {
            add_action('hh_dashboard_breadcrumb', [$this, '_addCreateCouponButton']);
        }

        public function _removeCouponFromCart(Request $request)
        {
            $cart = \Cart::get_inst()->getCart();
            if (isset($cart['cartOrigin'])) {
                $cart = $cart['cartOrigin'];
            }

            \Cart::get_inst()->setCart($cart);

            return $this->sendJson([
                'status' => 1,
                'message' => view('common.alert', ['type' => 'success', 'message' => __('The coupon code is removed')])->render(),
                'redirect' => checkout_url()
            ]);
        }

        public function _addCouponToCart(Request $request)
        {
            $coupon_code = request()->get('coupon');
            $service_id = request()->get('service_id');
            $service_type = request()->get('service_type');

            $post_types = Config::get('awebooking.post_types');
            $serviceObject = get_post($service_id, $service_type);
            if (!isset($post_types[$service_type]) || is_null($serviceObject)) {
                $this->sendJson([
                    'status' => 0,
                    'message' => view('common.alert', ['type' => 'danger', 'message' => __('This service is not available')])->render()
                ], true);
            }

            $coupon = $this->checkCouponByCode($coupon_code);

            if ($coupon['status'] == 0) {
                $this->sendJson([
                    'status' => 0,
                    'message' => view('common.alert', ['type' => 'danger', 'message' => $coupon['message']])->render()
                ], true);
            }

            $coupon = $coupon['coupon'];
            if (!empty($coupon->service_type) && $coupon->service_type !== $serviceObject->post_type) {
                $this->sendJson([
                    'status' => 0,
                    'message' => view('common.alert', ['type' => 'danger', 'message' => __('Coupon code does not apply to this service')])->render()
                ], true);
            }
            $couponPrice = (float)$coupon->coupon_price;
            $couponPriceHtml = '';
            $couponType = $coupon->price_type;

            $cart = \Cart::get_inst()->getCart();

            if (isset($cart['cartData']['coupon']) && isset($cart['cartOrigin'])) {
                $cart = $cart['cartOrigin'];
            }

            $cart['cartOrigin'] = $cart;

            $subTotal = $amount = 0;

            if ($couponType == 'percent') {
                $subTotal = $cart['subTotal'] - ($cart['subTotal'] * $couponPrice / 100);
                $couponPriceHtml = round($couponPrice, 2) . '%';
            } elseif ($couponType == 'fixed') {
                $subTotal = $cart['subTotal'] - $couponPrice;
                $couponPriceHtml = convert_price($couponPrice);
            }
            $taxRule = [];

            $taxData = \Cart::get_inst()->getTax($serviceObject->post_type);
            if ($taxData['included'] == 'off') {
                $taxRule = [
                    [
                        'unit' => 'tax',
                        'price' => $taxData['tax']
                    ]
                ];
            }
            $amount = \Cart::get_inst()->getAmount($subTotal, $taxRule);

            $cart['subTotal'] = $subTotal;
            $cart['amount'] = $amount;
            $cart['couponPrice'] = $couponPriceHtml;

            $cartData = $cart['cartData'];
            $coupon->couponPriceHtml = $couponPriceHtml;
            $cartData['coupon'] = $coupon;
            $cart['cartData'] = $cartData;

            \Cart::get_inst()->setCart($cart);

            return $this->sendJson([
                'status' => 1,
                'message' => view('common.alert', ['type' => 'success', 'message' => __('This coupon code has been successfully applied')])->render(),
                'redirect' => checkout_url()
            ]);
        }

        public function checkCouponByCode($couponCode = '')
        {
            if (empty(trim($couponCode))) {
                return [
                    'status' => 0,
                    'message' => __('This coupon is invalid')
                ];
            }
            $coupon_model = new Coupon();
            $coupon = $coupon_model->getCouponItem($couponCode, 'code');

            if (!$coupon) {
                return [
                    'status' => 0,
                    'message' => __('This coupon is invalid')
                ];
            }
            if ($coupon->status == 'off') {
                return [
                    'status' => 0,
                    'message' => __('This coupon is not available')
                ];
            }

            if (!empty($coupon->start_time) && !empty($coupon->end_time)) {
                $startTime = $coupon->start_time;
                $endTime = $coupon->end_time;
                $today = time();
                if ($startTime <= $endTime && $today >= $startTime && $today <= $endTime) {
                    [
                        'status' => 1,
                        'message' => __('This coupon is available'),
                        'coupon' => $coupon
                    ];
                } else {
                    return [
                        'status' => 0,
                        'message' => __('This coupon is not available')
                    ];
                }
            } else {
                return
                    [
                        'status' => 1,
                        'message' => __('This coupon is available'),
                        'coupon' => $coupon
                    ];
            }
            return
                [
                    'status' => 1,
                    'message' => __('This coupon is available'),
                    'coupon' => $coupon
                ];
        }

        public function _addCreateCouponButton()
        {
            $screen = current_screen();
            if ($screen == 'coupon') {
                echo view('dashboard.components.quick-add-coupon')->render();
            }
        }

        public function _getCouponItem(Request $request)
        {
            $coupon_id = request()->get('couponID');
            $coupon_encrypt = request()->get('couponEncrypt');
            if (!hh_compare_encrypt($coupon_id, $coupon_encrypt)) {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('this Coupon is invalid')
                ], true);
            }

            $coupon = new Coupon();
            $couponObject = $coupon->getById($coupon_id);
            if (!empty($couponObject) && is_object($couponObject)) {

                $html = view('dashboard.components.coupon-form', ['couponObject' => $couponObject])->render();

                $this->sendJson([
                    'status' => 1,
                    'html' => $html
                ], true);
            } else {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('this Coupon is invalid')
                ], true);
            }
        }

        public function _deleteCouponItem(Request $request)
        {
            $coupon_id = request()->get('couponID');
            $coupon_encrypt = request()->get('couponEncrypt');

            if (!hh_compare_encrypt($coupon_id, $coupon_encrypt)) {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('this Coupon is invalid')
                ], true);
            }
            $coupon = new Coupon();
            $couponObject = $coupon->getById($coupon_id);

            if (!empty($couponObject) && is_object($couponObject)) {
                $deleted = $coupon->deleteCoupon($coupon_id);

                if ($deleted) {
                    $this->sendJson([
                        'status' => 1,
                        'title' => __('System Alert'),
                        'message' => __('this Coupon is deleted'),
                        'reload' => true
                    ], true);
                } else {
                    $this->sendJson([
                        'status' => 0,
                        'title' => __('System Alert'),
                        'message' => __('Can not delete this coupon')
                    ], true);
                }
            } else {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('this Coupon is invalid')
                ], true);
            }
        }

        public function _updateCouponItem(Request $request)
        {
            $coupon_id = request()->get('couponID');
            $coupon_encrypt = request()->get('couponEncrypt');

            if (!hh_compare_encrypt($coupon_id, $coupon_encrypt)) {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('this Coupon is invalid')
                ], true);
            }
            $coupon = new Coupon();
            $couponObject = $coupon->getById($coupon_id);

            if (!empty($couponObject) && is_object($couponObject)) {
                $coupon_code = request()->get('coupon_code');
                $coupon_description = set_translate('coupon_description');
                $price_type = request()->get('coupon_type', 'fixed');
                $coupon_price = request()->get('coupon_price', 0);
                $coupon_start = request()->get('coupon_start', '');
                $coupon_end = request()->get('coupon_end', '');
                $coupon_service_type = request()->get('coupon_service_type', '');

                if ($coupon_code && $coupon_start && $coupon_end) {
                    $data = [
                        'coupon_code' => $coupon_code,
                        'coupon_description' => $coupon_description,
                        'price_type' => $price_type,
                        'coupon_price' => (float)$coupon_price,
                        'start_time' => strtotime($coupon_start),
                        'end_time' => strtotime($coupon_end),
                        'service_type' => $coupon_service_type
                    ];
                    $updated = $coupon->updateCoupon($data, $coupon_id);

                    if (!is_null($updated)) {
                        $this->sendJson([
                            'status' => 1,
                            'title' => __('System Alert'),
                            'message' => __('Updated Successfully'),
                        ], true);
                    } else {
                        $this->sendJson([
                            'status' => 0,
                            'title' => __('System Alert'),
                            'message' => __('Can not update this coupon')
                        ], true);
                    }
                } else {
                    $this->sendJson([
                        'status' => 0,
                        'title' => __('System Alert'),
                        'message' => __('Some fields is incorrect')
                    ], true);
                }

            } else {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('this Coupon is invalid')
                ], true);
            }
        }

        public function _changeCouponStatus(Request $request)
        {
            $coupon_id = request()->get('couponID');
            $coupon_encrypt = request()->get('couponEncrypt');
            $status = request()->get('val', 'off');

            if (!hh_compare_encrypt($coupon_id, $coupon_encrypt)) {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('this Coupon is invalid')
                ], true);
            }

            $coupon = new Coupon();
            $data = [
                'status' => empty($status) ? 'off' : $status
            ];
            $updated = $coupon->updateCoupon($data, $coupon_id);

            if ($updated) {
                $this->sendJson([
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Updated Successfully'),
                ], true);
            } else {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Can not update this coupon')
                ], true);
            }
        }

        public function _addNewCoupon(Request $request)
        {
            $coupon_code = request()->get('coupon_code');
            $coupon_description = set_translate('coupon_description');
            $price_type = request()->get('coupon_type', 'fixed');
            $coupon_price = request()->get('coupon_price', 0);
            $coupon_start = request()->get('coupon_start', '');
            $coupon_end = request()->get('coupon_end', '');
            $status = 'on';

            $coupon = new Coupon();
            $hasCoupon = $coupon->getByCode($coupon_code);
            if (!empty($hasCoupon) && is_object($hasCoupon)) {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('this Coupon is exists')
                ], true);
            }
            if ($coupon_code && $coupon_start && $coupon_end) {
                $data = [
                    'coupon_code' => $coupon_code,
                    'coupon_description' => $coupon_description,
                    'price_type' => $price_type,
                    'coupon_price' => (float)$coupon_price,
                    'start_time' => strtotime($coupon_start),
                    'end_time' => strtotime($coupon_end),
                    'status' => $status,
                    'author' => get_current_user_id(),
                    'created_at' => time()
                ];

                $newCoupon = $coupon->createCoupon($data);
                if ($newCoupon) {
                    $this->sendJson([
                        'status' => 1,
                        'title' => __('System Alert'),
                        'message' => __('Created Successfully'),
                        'reload' => true
                    ], true);
                } else {
                    $this->sendJson([
                        'status' => 0,
                        'title' => __('System Alert'),
                        'message' => __('Can not create this coupon')
                    ], true);
                }
            } else {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Some fields is incorrect')
                ], true);
            }
        }

        public function _allCoupon(Request $request, $page = 1)
        {
            $folder = $this->getFolder();
            $search = request()->get('_s');
            $orderBy = request()->get('orderby', 'coupon_id');
            $order = request()->get('order', 'desc');
            $status = request()->get('status', '');
            $data = [
                'search' => $search,
                'page' => $page,
                'orderby' => $orderBy,
                'order' => $order,
                'status' => $status
            ];
            if (!is_admin()) {
                $data['author'] = get_current_user_id();
            }
            $allCoupons = $this->getAllCoupons(
                $data
            );

            return view("dashboard.screens.{$folder}.coupon", ['bodyClass' => 'hh-dashboard', 'allCoupons' => $allCoupons]);
        }

        public function getAllCoupons($data = [])
        {
            $coupon = new Coupon();
            return $coupon->getAllCoupons($data);
        }

    }

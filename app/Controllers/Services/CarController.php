<?php

namespace App\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use App\Models\CarPrice;
use App\Models\Comment;
use App\Models\TermRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Sentinel;

class CarController extends Controller
{
    public function __construct()
    {
        add_action('hh_dashboard_breadcrumb', [$this, '_addCreateCarButton']);
    }

    public function assignDataByUserID($user_id, $user_assign)
    {
        $model = new Car();
        $model->updateAuthor(['author' => $user_assign], $user_id);
    }

    public function deleteDataByUserID($user_id)
    {
        $model = new Car();
        $allCar = $model->getAllCars([
            'author' => $user_id,
            'number' => -1
        ]);
        if ($allCar['total'] > 0) {
            foreach ($allCar['results'] as $item) {
                $model->deleteCarItem($item->post_id);
            }
        }
    }

    public function _bulkActions(Request $request)
    {
        $action = request()->get('action', '');
        $post_id = request()->get('post_id', '');

        if (!empty($action) && !empty($post_id)) {
            $post_id = explode(',', $post_id);

            $carModel = new Car();
            switch ($action) {
                case 'delete':
                    $carModel->whereIn('post_id', $post_id)->delete();
                    $commentModel = new Comment();
                    $commentModel->whereIn('post_id', $post_id)->where('post_type', 'car')->delete();
                    $carPriceModel = new CarPrice();
                    $carPriceModel->whereIn('car_id', $post_id)->delete();
                    $termRelationModel = new TermRelation();
                    $termRelationModel->whereIn('service_id', $post_id)->delete();
                    break;
                case 'publish':
                case 'pending':
                case 'draft':
                case 'trash':
                    $carModel->updateMultiCar([
                        'status' => $action
                    ], $post_id);
                    break;
            }
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Bulk action successfully')
            ], true);
        }
        $this->sendJson([
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Data invalid')
        ], true);
    }

    public function _getCarAvailabilityTimeSingle(Request $request)
    {
        $start = request()->get('start');
        $end = request()->get('end');
        $car_id = request()->get('car_id');

        $carModel = new Car();
        $carObject = $carModel->getById($car_id);

        if ($start && $end && !is_null($carObject)) {
            $times = list_hours(15);
            $result = $times;
            if (!empty($carObject->quantity) && $carObject->quantity > 0) {
                $bookingModel = new Booking();

                $start = strtotime($start);
                $end = strtotime($end);

                $booked = $bookingModel->selectRaw("*")->where('service_id', $car_id)->whereRaw("status IN ('completed', 'incomplete')
                    AND
                    service_type = 'car'
                    AND
                    (
                        (start_date <= {$start} AND end_date >= {$end})
                        OR
                        (start_date >= {$start} AND end_date <= {$end})
                        OR
                        (start_date <= {$start} AND end_date >= {$start})
                        OR
                        (start_date <= {$end} AND end_date >= {$end})
                    )")->get();

                if (!is_null($booked)) {
                    $arr_total = [];
                    foreach ($booked as $item) {
                        $start_time = $item['start_time'];
                        $end_time = $item['end_time'];
                        for ($i = $start_time; $i <= $end_time; $i = strtotime('+15 minutes', $i)) {
                            if (isset($arr_total[$i])) {
                                $arr_total[$i] = $arr_total[$i] + $item['number'];
                            } else {
                                $arr_total[$i] = $item['number'];
                            }
                        }
                    }

                    if (!empty($arr_total)) {
                        foreach ($arr_total as $key => $value) {
                            if ($value >= $carObject->quantity) {
                                $hour = date('h:i a', $key);
                                if (isset($result[strtoupper($hour)])) {
                                    unset($result[strtoupper($hour)]);
                                }
                            }
                        }
                    }
                }
            }

            $return = '';
            foreach ($result as $key => $time) {
                $return .= '<div class="item" data-value="' . esc_attr($key) . '">' . esc_html($time) . '</div>';
            }
            return $this->sendJson([
                'status' => 1,
                'html' => $return
            ]);
        } else {
            return $this->sendJson([
                'status' => 0,
                'message' => view('common.alert', ['type' => 'danger', __('The data is invalid')])->render()
            ]);
        }
    }

    public function _sendEnquiryForm(Request $request)
    {
        $name = request()->get('name');
        $email = request()->get('email');
        $message = request()->get('message');

        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'message' => 'required|min:10'
            ],
            [
                'name.required' => __('Name is required'),
                'email.required' => __('Email is required'),
                'email.email' => __('This email is incorrect'),
                'message.required' => __('Message is required')
            ]
        );
        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'message' => view('common.alert', ['type' => 'danger', 'message' => $validator->errors()->first()])->render()
            ]);
        }
        $post_id = request()->get('post_id');
        $post_encrypt = request()->get('post_encrypt');
        if (!hh_compare_encrypt($post_id, $post_encrypt)) {
            return $this->sendJson([
                'status' => 0,
                'message' => view('common.alert', ['type' => 'danger', 'message' => __('This service is invalid')])->render()
            ]);
        }
        $post = get_post($post_id, 'car');
        $admin = get_admin_user();
        $partner = get_user_by_id($post->author);

        send_mail(esc_html($email), esc_html($name), $admin->email, sprintf(__('[%s] Have a booking request from [Car ID: %s] -  %s'), get_option('site_name'), $post_id, $post->post_title), balanceTags($message));
        send_mail(esc_html($email), esc_html($name), $partner->email, sprintf(__('[%s] Have a booking request from [Car ID: %s] - %s'), get_option('site_name'), $post_id, $post->post_title), balanceTags($message));
        return $this->sendJson([
            'status' => 1,
            'message' => view('common.alert', ['type' => 'success', 'message' => __('Sent! Please wait for a response from the partner')])->render()
        ]);
    }

    public function listOfCars($data = [])
    {
        $model = new Car();
        return $model->listOfCars($data);
    }

    public function _addToCartCar(Request $request, $api = false)
    {
        $carID = (int)request()->get('carID');
        $carEncrypt = request()->get('carEncrypt');
        $startDate = request()->get('checkIn', '');
        $endDate = request()->get('checkOut', '');
        $startTime = request()->get('startTime', '');
        $endTime = request()->get('endTime', '');
        $equipments = request()->get('equipment');
        $insurances = request()->get('insurancePlan');
        $number = (int)request()->get('number');

        if (hh_compare_encrypt($carID, $carEncrypt) && $startDate && $endDate && $number > 0) {
            //Check availability with date and time
            $carModel = new Car();
            $carObject = $carModel->getById($carID);

            $startDateTimeStr = strtotime($startDate . ' ' . $startTime);
            $endDateTimeStr = strtotime($endDate . ' ' . $endTime);

            $booking_type = get_car_booking_type();

            if ($startDateTimeStr >= $endDateTimeStr || empty($startTime) || empty($endTime)) {
            	if($api){
            		return [
			            'status'  => 0,
			            'message' => __( 'Please select a valid datetime' )
		            ];
	            }else {
		            $this->sendJson( [
			            'status'  => 0,
			            'message' => view( 'common.alert', [
				            'type'    => 'danger',
				            'message' => __( 'Please select a valid datetime' )
			            ] )->render()
		            ], true );
	            }
            }

            $number_hour = hh_date_diff($startDateTimeStr, $endDateTimeStr, 'hour');
            $number_day = hh_date_diff(strtotime($startDate), strtotime($endDate)) + 1;

            $equipment_data = $this->getEquipmentData($carObject->equipments, $equipments);

            if ($booking_type == 'hour') {
                $insurance_data = $this->getInsuranceData($carObject->equipments, $equipments, $number_hour);
            } else {
                $insurance_data = $this->getInsuranceData($carObject->insurance_plan, $insurances, $number_day);
            }
            $data = [
                'carID' => $carID,
                'number' => $number,
                'startDate' => strtotime($startDate),
                'endDate' => strtotime($endDate),
                'startTime' => strtotime($startTime),
                'endTime' => strtotime($endTime),
                'startDateTime' => $startDateTimeStr,
                'endDateTime' => $endDateTimeStr,
                'bookingType' => $booking_type,
                'numberHour' => $number_hour,
                'numberDay' => $number_day,
                'equipmentData' => $equipment_data['data'],
                'insuranceData' => $insurance_data['data']
            ];

            $data_check_availability = [
                'carID' => $carID,
                'checkIn' => $startDate,
                'checkOut' => $endDate,
                'checkInTime' => $startTime,
                'checkOutTime' => $endTime,
                'quantity' => $carObject->quantity,
                'number' => $number
            ];

            if ($number > $carObject->quantity) {
                $is_available = [
                    'status' => 0
                ];
            } else {
                $is_available = $carModel->checkAvailable($data_check_availability);
            }

            if ($is_available['status'] == 0) {
            	if($api){
					return [
						'status'  => 0,
						'message' => sprintf(__( 'The quantity need to less than %s' ), $carObject->quantity)
					];
	            }else {
		            $this->sendJson( [
			            'status'  => 0,
			            'message' => view( 'common.alert', [
				            'type'    => 'danger',
				            'message' => sprintf(__( 'The quantity need to less than %s' ), $carObject->quantity)
			            ] )->render()
		            ], true );
	            }
            } elseif ($is_available['status'] == 1) {
            	if($api){
					return [
						'status'  => 0,
						'message' => __( 'This car is unavailability' )
					];
	            }else {
            		if($api){
            			return [
				            'status'  => 0,
				            'message' => __( 'This car is unavailability' )
			            ];
		            }else {
			            $this->sendJson( [
				            'status'  => 0,
				            'message' => view( 'common.alert', [
					            'type'    => 'danger',
					            'message' => __( 'This car is unavailability' )
				            ] )->render()
			            ], true );
		            }
	            }
            } elseif ($is_available['status'] == 2) {
            	if($api){
            		return [
			            'status'  => 0,
			            'message' => __( 'This car is unavailability' )
		            ];
	            }else {
		            $this->sendJson( [
			            'status'  => 0,
			            'message' => view( 'common.alert', [
				            'type'    => 'danger',
				            'message' => __( 'This car is unavailability' )
			            ] )->render()
		            ], true );
	            }
            } elseif ($is_available['status'] == 3) {
            	if($api){
					return [
						'status'  => 0,
						'message' => sprintf(__( 'The number maximum are %s' ), $is_available['available'])
					];
	            }else {
		            $this->sendJson( [
			            'status'  => 0,
			            'message' => view( 'common.alert', [
				            'type'    => 'danger',
				            'message' => sprintf(__( 'The number maximum are %s' ), $is_available['available'])
			            ] )->render()
		            ], true );
	            }
            } else {
                $priceData = $this->calcRealPrice($carID, $startDate, $endDate, $startTime, $endTime, $equipments, $insurances, $number);

                $basePrice = $priceData['base_price'];
                $equipmentPrice = $priceData['equipment_price'];
                $insurancePrice = $priceData['insurance_price'];

                $rules = [
                    [
                        'unit' => '+',
                        'price' => $basePrice
                    ],
                    [
                        'unit' => '+',
                        'price' => $equipmentPrice
                    ],
                    [
                        'unit' => '+',
                        'price' => $insurancePrice
                    ]
                ];

                $taxRule = [];
                $taxData = \Cart::get_inst()->getTax('car');

                if ($taxData['included'] == 'off') {
                    $taxRule = [
                        [
                            'unit' => 'tax',
                            'price' => $taxData['tax']
                        ]
                    ];
                }

                $totalData = \Cart::get_inst()->totalCalculation($rules, $taxRule);

                $cartData = [
                    'serviceID' => $carID,
                    'serviceObject' => serialize($carObject),
                    'serviceType' => 'car',
                    'basePrice' => $basePrice,
                    'equipmentPrice' => $equipmentPrice,
                    'insurancePrice' => $insurancePrice,
                    'subTotal' => $totalData['subTotal'],
                    'tax' => $taxData,
                    'amount' => $totalData['amount'],
                    'cartData' => $data,
                ];

                $cartData = apply_filters('hh_cart_data_before_add_to_cart', $cartData);

	            if($api){
		            return [
			            'status' => 1,
			            'cart' => $cartData
		            ];
	            }

                \Cart::get_inst()->setCart($cartData);

                return $this->sendJson(array(
                    'status' => true,
                    'redirect' => checkout_url()
                ));
            }
        } else {
	        if($api){
		        return [
			        'status'  => 0,
			        'message' => __( 'The data is invalid' )
		        ];
	        }else {
		        $this->sendJson( [
			        'status'  => 0,
			        'message' => view( 'common.alert', [ 'type'    => 'warning',
			                                             'message' => __( 'The data is invalid' )
			        ] )->render()
		        ], true );
	        }
        }
	    if($api){
		    return [
			    'status'  => 0,
			    'message' => __( 'The data is invalid' )
		    ];
	    }else {
		    $this->sendJson( [
			    'status'  => 0,
			    'message' => view( 'common.alert', [ 'type'    => 'warning',
			                                         'message' => __( 'The data is invalid' )
			    ] )->render()
		    ], true );
	    }
    }

    public function _getCarPriceRealTime(Request $request)
    {
        $carID = request()->get('carID');
        $carEncrypt = request()->get('carEncrypt');

        $startDate = request()->get('checkIn');
        $endDate = request()->get('checkOut');
        $startTime = request()->get('startTime');
        $endTime = request()->get('endTime');
        $equipments = request()->get('equipment');
        $insurances = request()->get('insurancePlan');
        $number = (int)request()->get('number');

        if (hh_compare_encrypt($carID, $carEncrypt) && $startDate && $endDate && $number && $startTime && $endTime) {
            $startDateTime = strtotime($startDate . ' ' . $startTime);
            $endDateTime = strtotime($endDate . ' ' . $endTime);
            if ($startDateTime < $endDateTime) {
                $priceData = $this->calcRealPrice($carID, $startDate, $endDate, $startTime, $endTime, $equipments, $insurances, $number);
                $total = array_sum($priceData);
                $this->sendJson([
                    'status' => 1,
                    'html' => view('frontend.car.calculate-price-render', ['total' => $total])->render()
                ], true);
            } else {
                $this->sendJson([
                    'status' => 0,
                    'html' => '',
                    'message' => __('The data is invalid')
                ], true);
            }
        } else {
            $this->sendJson([
                'status' => 1,
                'html' => '',
                'message' => __('The data is invalid')
            ], true);
        }
    }

    public function calcRealPrice($carID, $startDate, $endDate, $startTime, $endTime, $equipments, $insurances, $number)
    {
        $booking_type = get_car_booking_type();

        $startDateStr = strtotime($startDate);
        $endDateStr = strtotime($endDate);

        $priceModel = new CarPrice();
        $customPrice = $priceModel->getPriceItems($carID, $startDateStr, $endDateStr);

        $carModel = new Car();
        $carObject = $carModel->getById($carID);

        $startDateTimeStr = strtotime($startDate . ' ' . $startTime);
        $endDateTimeStr = strtotime($endDate . ' ' . $endTime);
        $number_hour = hh_date_diff($startDateTimeStr, $endDateTimeStr, 'hour', false);

        if ($booking_type == 'hour') {
            $customPriceByDay = [];
            for ($i = $startDateStr; $i <= $endDateStr; $i = strtotime('+1 day', $i)) {
                $inCustom = false;
                if ($customPrice['total'] > 0) {
                    foreach ($customPrice['results'] as $item) {
                        if ($i >= $item->start_date && $i <= $item->end_date) {
                            $startDateTime = $startDate . ' ' . $startTime;
                            $endDateTime = $endDate . ' ' . $endTime;
                            if ($i == $startDateStr && $i == $endDateStr) {
                                //Need calc hour with time of start date and end date
                                $startDateTime = $startDate . ' ' . $startTime;
                                $endDateTime = $endDate . ' ' . $endTime;
                            } elseif ($i == $startDateStr && $i < $endDateStr) {
                                //Need calc hour with hour of start date and midnight start date
                                $startDateTime = $startDate . ' ' . $startTime;
                                $endDateTime = date('Y-m-d', strtotime('+ 1 day', strtotime($startDate))) . ' 12:00 am';
                            } elseif ($i == $endDateStr && $i > $startDateStr) {
                                //Need calc hour with 00:00 AM end date to hour of end date
                                $startDateTime = $endDate . ' 12:00 am';
                                $endDateTime = $endDate . ' ' . $endTime;
                            } elseif ($i > $startDateStr && $i < $endDateStr) {
                                //Full date
                                $startDateTime = date('Y-m-d', $i) . ' 12:00 am';
                                $endDateTime = date('Y-m-d', strtotime('+ 1 day', $i)) . ' 12:00 am';
                            }
                            $hour = hh_date_diff(strtotime($startDateTime), strtotime($endDateTime), 'hour', false);
                            $customPriceByDay[] = [
                                'hour' => $hour,
                                'price' => (float)$item->price
                            ];

                            $inCustom = true;
                            break;
                        }
                    }
                }
                if (!$inCustom) {
                    $startDateTime = $startDate . ' ' . $startTime;
                    $endDateTime = $endDate . ' ' . $endTime;
                    if ($i == $startDateStr && $i == $endDateStr) {
                        //Need calc hour with time of start date and end date
                        $startDateTime = $startDate . ' ' . $startTime;
                        $endDateTime = $endDate . ' ' . $endTime;
                    } elseif ($i == $startDateStr && $i < $endDateStr) {
                        //Need calc hour with hour of start date and midnight start date
                        $startDateTime = $startDate . ' ' . $startTime;
                        $endDateTime = date('Y-m-d', strtotime('+ 1 day', strtotime($startDate))) . ' 12:00 am';
                    } elseif ($i == $endDateStr && $i > $startDateStr) {
                        //Need calc hour with 00:00 AM end date to hour of end date
                        $startDateTime = $endDate . ' 12:00 am';
                        $endDateTime = $endDate . ' ' . $endTime;
                    } elseif ($i > $startDateStr && $i < $endDateStr) {
                        //Full date
                        $startDateTime = date('Y-m-d', $i) . ' 12:00 am';
                        $endDateTime = date('Y-m-d', strtotime('+ 1 day', $i)) . ' 12:00 am';
                    }
                    $hour = hh_date_diff(strtotime($startDateTime), strtotime($endDateTime), 'hour', false);
                    $customPriceByDay[] = [
                        'hour' => $hour,
                        'price' => (float)$carObject->base_price
                    ];
                }
            }

            $total = 0;
            if (!empty($customPriceByDay)) {
                foreach ($customPriceByDay as $item) {
                    $total += (float)$item['hour'] * $item['price'];
                }
            }

            //Calc price equipments
            $price_equipments = $this->getEquipmentData($carObject->equipments, $equipments)['price'];

            $price_insurance = $this->getInsuranceData($carObject->insurance_plan, $insurances, $number_hour)['price'];

            return [
                'base_price' => ($total * $number),
                'equipment_price' => ($price_equipments * $number_hour * $number),
                'insurance_price' => ($price_insurance * $number)
            ];
        } else {
            $number_day = hh_date_diff($startDateStr, $endDateStr) + 1;
            $discount = $carObject->discount_by_day;
            $use_discount = false;
            $price_discount = $carObject->base_price;
            if (!empty($discount)) {
                $discount = maybe_unserialize($discount);
                if (!empty($discount)) {
                    foreach ($discount as $item) {
                        if ($number_day >= $item['from'] && $number_day <= $item['to']) {
                            $use_discount = true;
                            $price_discount = $item['price'];
                            break;
                        }
                    }
                }
            }

            $total = 0;

            if (!$use_discount) {
                for ($i = $startDateStr; $i <= $endDateStr; $i = strtotime('+1 day', $i)) {
                    $inCustom = false;
                    if ($customPrice['total'] > 0) {
                        foreach ($customPrice['results'] as $item) {
                            if ($i >= $item->start_date && $i <= $item->end_date) {
                                $total += (float)$item->price;
                                $inCustom = true;
                                break;
                            }
                        }
                    }
                    if (!$inCustom) {
                        $total += (float)$carObject->base_price;
                    }
                }
            } else {
                $total = $price_discount * $number_day;
            }

            //Calc price equipments
            $price_equipments = $this->getEquipmentData($carObject->equipments, $equipments)['price'];

            $price_insurance = $this->getInsuranceData($carObject->insurance_plan, $insurances, $number_day)['price'];

            return [
                'base_price' => ($total * $number),
                'equipment_price' => ($price_equipments * $number_day * $number),
                'insurance_price' => ($price_insurance * $number)
            ];
        }
    }

    public function getInsuranceData($insurance_plan, $insurance_selected, $time)
    {
        $price_insurance = 0;
        $insurance_data = [];

        $insurance_plan = maybe_unserialize($insurance_plan);

        if (!empty($insurance_selected) && !empty($insurance_plan)) {
            foreach ($insurance_plan as $item) {
                if (in_array($item['name_unique'], $insurance_selected)) {
                    $price = (float)$item['price'];
                    if ($item['fixed'] != 'on') {
                        $price = $price * (float)$time;
                    }

                    $price_insurance += $price;

                    $item['custom_price'] = $price;
                    $insurance_data[] = $item;
                }
            }
        }

        return [
            'price' => $price_insurance,
            'data' => $insurance_data
        ];
    }

    public function getEquipmentData($equipmentObject, $eqIDs)
    {
        $equipmentObject = maybe_unserialize($equipmentObject);
        $equipmentTax = get_terms('car-equipment', true);

        $termData = [];
        if (count($equipmentTax) > 0) {
            if (!empty($equipmentTax)) {
                foreach ($equipmentTax as $term) {
                    $termData[$term->term_id] = [
                        'title' => $term->term_title,
                        'price' => $term->term_price,
                        'term' => $term
                    ];
                }
            }
        }

        //Calc price equipments
        $price_equipments = 0;
        $equipment_data = [];
        if (!empty($eqIDs)) {
            foreach ($eqIDs as $id) {
                if (isset($termData[$id]) && isset($equipmentObject[$id])) {
                    $price = $equipmentObject[$id]['price'];
                    if (!$equipmentObject[$id]['custom']) {
                        $price = $termData[$id]['price'];
                    }
                    $price_equipments += $price;
                    $term = $termData[$id]['term'];
                    $term->custom_price = $price;
                    $equipment_data[] = $term;
                }
            }
        }

        return [
            'price' => $price_equipments,
            'data' => $equipment_data
        ];
    }

    public function _getCarAvailabilitySingle(Request $request)
    {
        $events['events'] = [];
        $startTime = strtotime(request()->get('startTime'));
        $endTime = strtotime(request()->get('endTime'));
        $carID = request()->get('carID');
        $carEncrypt = request()->get('carEncrypt');

        if ($startTime && $endTime && hh_compare_encrypt($carID, $carEncrypt)) {
            $price_model = new CarPrice();
            $priceItems = $price_model->getPriceItems($carID, $startTime, $endTime);
            $carObject = $this->getById($carID);
            $price = (float)$carObject->base_price;
            $quatity = (int)$carObject->quantity;
            $bookingModel = new Booking();
            $bookingItems = $bookingModel->getBookingItems($carID, $startTime, $endTime);
            for ($i = $startTime; $i <= $endTime; $i = strtotime('+1 day', $i)) {
                $status = 'available';
                $event = convert_price($price);

                if ($priceItems['total'] > 0) {
                    foreach ($priceItems['results'] as $range) {
                        if ($i >= $range->start_date && $i <= $range->end_date) {
                            $event = convert_price($range->price);
                            if ($range->available == 'off') {
                                $status = 'not_available';
                                $event = 'Unavailable';
                            }
                            break;
                        }
                    }
                }

                if (!empty($quatity) && $quatity > 0) {
                    if ($bookingItems['total'] > 0) {
                        $number_booked = 0;
                        foreach ($bookingItems['results'] as $range) {
                            if ($i >= $range->start_date && $i <= $range->end_date) {
                                $number_booked += $range->number;
                            }
                        }
                        if ($number_booked >= $quatity) {
                            $status = 'not_available';
                            $event = 'Unavailable';
                        }
                    }
                }

                $events['events'][] = [
                    'start' => date('Y-m-d', $i),
                    'end' => date('Y-m-d', $i),
                    'status' => $status,
                    'event' => $event
                ];
            }
        }

        $this->sendJson($events, true);
    }


    public function _getCarSingle(Request $request, $car_id, $car_name = null)
    {
        $carObject = $this->getById($car_id, true);

        if (is_null($carObject) || !$carObject || $carObject->status != 'publish') {
            return view('frontend.404');
        } else {
            return view('frontend.car.default');
        }
    }

    public function _getSearchResult()
    {

        $car = new Car();
        $post_data = request()->all();
        $layout = request()->get('layout', 'grid');
        $showmap = request()->get('showmap', 'yes');
        if (!in_array($layout, ['list', 'grid'])) {
            $layout = 'grid';
        }

        $number_default = 6;
        if ($showmap == 'no') {
            $number_default = 8;
        }

        $post_data['number'] = $number_default;

        $data = $car->getSearchResult($post_data);

        $search_string = view('frontend.car.search.search-string', [
            'count' => $data['total'],
            'address' => request()->get('address'),
            'check_in' => request()->get('checkIn'),
            'check_out' => request()->get('checkOut'),
            'check_in_time' => request()->get('checkInTime'),
            'check_out_time' => request()->get('checkOutTime')
        ])->render();

        if (isset($post_data['_token'])) {
            unset($post_data['_token']);
        }

        $url_temp = http_build_query($post_data);

        $locations = [];
        $html = '';

        if ($data['total'] > 0) {
            if ($layout == 'grid') {
                $class_wrapper = 'col-lg-6 col-md-6';
                if ($showmap == 'no') {
                    $class_wrapper = 'col-12 col-md-6 col-lg-3';
                }

            } else {
                $class_wrapper = 'col-lg-12';
                if ($showmap == 'no') {
                    $class_wrapper = 'col-12 col-sm-6 col-md- 6 col-lg-6';
                }
            }

            foreach ($data['results'] as $key => $item) {
                $html .= view('frontend.car.loop.' . $layout, [
                    'item' => $item,
                    'class_wrapper' => $class_wrapper
                ])->render();

                $locations[] = [
                    'lat' => $item->location_lat,
                    'lng' => $item->location_lng,
                    'price' => convert_price($item->base_price),
                    'post_id' => $item->post_id,
                    'title' => get_translate($item->post_title),
                    'url' => get_car_permalink($item->post_id, $item->post_slug),
                    'thumbnail' => get_attachment_url($item->thumbnail_id, [75, 75]),
                    'address' => get_short_address($item)
                ];
            }
        } else {
            $lat = request()->get('lat', 0);
            $lng = request()->get('lng', 0);
            $locations[] = [
                'lat' => $lat,
                'lng' => $lng
            ];
        }

        $pag = view('frontend.components.search-pagination', [
            'total' => $data['total'],
            'query_string' => '?' . $url_temp,
            'current_url' => $post_data['current_url'],
            'page' => $post_data['page'],
            'number' => isset($post_data['number']) ? intval($post_data['number']) : $number_default
        ])->render();

        $this->sendJson([
            'status' => true,
            'search_string' => $search_string,
            'html' => $html,
            'pag' => $pag,
            'locations' => $locations,
            'total' => $data['total']
        ], true);
    }

    public function _searchPage($page = '1')
    {
        return view("frontend.car.search.search", ['page' => $page]);
    }

    public function getMinMaxPrice()
    {
        $car_model = new Car();
        $minMaxPrice = $car_model->getMinMaxPrice();
        if (!isset($minMaxPrice['min']) || empty($minMaxPrice['min'])) {
            $minMaxPrice['min'] = 0;
        }
        if (!isset($minMaxPrice['max'])) {
            $minMaxPrice['max'] = 500;
        }

        $minMaxPrice['min'] = convert_price($minMaxPrice['min'], false, false);
        $minMaxPrice['max'] = convert_price($minMaxPrice['max'], false, false);

        return $minMaxPrice;
    }

    public function _addCreateCarButton()
    {
        $screen = current_screen();
        if ($screen == 'my-car') {
            echo view("dashboard.components.services.car.quick-add-car")->render();
        }
    }

    public function _deleteCarItem(Request $request)
    {
        $car_id = request()->get('serviceID');
        $car_encrypt = request()->get('serviceEncrypt');

        if (!hh_compare_encrypt($car_id, $car_encrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('The Car is not exist')
            ], true);
        }

        $car_model = new Car();

        $delete = $car_model->deleteCarItem($car_id);
        if ($delete) {
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Successfully Deleted'),
                'reload' => true
            ], true);
        }

        $this->sendJson([
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Has error when delete this Car')
        ], true);
    }

    public function _changeStatusCar(Request $request)
    {
        $car_id = request()->get('serviceID');
        $car_encrypt = request()->get('serviceEncrypt');
        $status = request()->get('status', '');

        if (!hh_compare_encrypt($car_id, $car_encrypt) || !$status) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('The data is invalid')
            ], true);
        }

        $car_model = new Car();
        $updated = $car_model->updateStatus($car_id, $status);
        if (!is_null($updated)) {
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Updated Successfully'),
                'reload' => true
            ], true);
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Have error when saving')
            ], true);
        }
    }

    public function _editCar(Request $request, $id = null)
    {
        $carModel = new Car();
        $carObject = $carModel->getById($id);
        if (!is_null($carObject) && user_can_edit_service($carObject)) {
            $folder = $this->getFolder();
            return view("dashboard.screens.{$folder}.services.car.edit-car", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'newCar' => $id]);
        } else {
            return redirect()->to(dashboard_url('my-car'));
        }
    }

    public function _myCar(Request $request, $page = 1)
    {
        $folder = $this->getFolder();

        $search = request()->get('_s');
        $orderBy = request()->get('orderby', 'post_id');
        $order = request()->get('order', 'desc');
        $status = request()->get('status');

        $car = new Car();
        $allCars = $car->getAllCars([
            'search' => $search,
            'orderby' => $orderBy,
            'order' => $order,
            'status' => $status,
            'page' => $page
        ]);

        return view("dashboard.screens.{$folder}.services.car.my-car", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'allCars' => $allCars]);
    }

    public function _addNewCar(Request $request)
    {
        $folder = $this->getFolder();
        $car = new Car();
        $newCar = $car->createCar();
        return view("dashboard.screens.{$folder}.services.car.add-new-car", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'newCar' => $newCar]);
    }

    public function getById($car_id, $global = false)
    {
        $car_object = new Car();
        $post_item = $car_object->getById($car_id);
        if (!is_null($post_item)) {
            $post_item = $this->setup_post_data($post_item);
        }
        if ($global) {
            global $post;
            $post = $post_item;
        }

        return $post_item;
    }

    public function getByName($car_name, $global = false)
    {
        $car_object = new Car();
        $post_item = $car_object->getByName($car_name);
        if (!is_null($post_item)) {
            $post_item = $this->setup_post_data($post_item);
        }
        if ($global) {
            global $post;
            $post = $post_item;
        }

        return $post_item;
    }


    public function setup_post_data($post)
    {
        return $this->_storeData($post);
    }

    private function _storeData($post)
    {
        $term_relation_object = new TermRelation();

        $post->review_count = get_comment_number($post->post_id, 'car');

        $tax = get_taxonomies('car');
        foreach ($tax as $key => $tax_name) {
            $name = 'tax_' . Str::slug($tax_name, '_');
            $post->$name = $term_relation_object->get_the_terms($post->post_id, 'car', $key);
        }

        return $post;
    }

    public function _updateCar(Request $request)
    {
        $step = request()->get('step', 'next');
        $event = request()->get('option_event', 'button');
        $redirect = request()->get('redirect');
        $post_title_field = 'post_title';
        if (is_multi_language()) {
            $current_lang = get_current_language();
            $post_title_field .= '_' . $current_lang;
        }

        $fields = request()->get('currentOptions');
        $fields = unserialize(base64_decode($fields));
        if ($fields) {
            $postID = request()->get('postID');
            $postEncrypt = request()->get('postEncrypt');
            if (!hh_compare_encrypt($postID, $postEncrypt)) {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Can not save meta for this service')
                ], true);
            }
            $car = new Car();
            $carObject = $car->getById($postID);
            if (!is_object($carObject)) {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('This car is not available')
                ], true);
            }
            $data = [];
            if ($carObject->status == 'revision') {
                $data['status'] = 'pending';
            }
            foreach ($fields as $field) {
                $field = \ThemeOptions::mergeField($field);
                $value = \ThemeOptions::fetchField($field);
                if (!$field['excluded'] && !empty($value)) {
                    if ($field['field_type'] == 'meta') {
                        $data[$field['id']] = $value;
                    } elseif ($field['field_type'] == 'taxonomy') {
                        $value = (array)$value;
                        $taxonomy = explode(':', $field['choices'])[1];
                        $termRelation = new TermRelation();
                        $termRelation->deleteRelationByServiceID($postID, $taxonomy);
                        foreach ($value as $termID) {
                            $termRelation->createRelation($termID, $postID, 'car');
                        }
                        $data[$field['id']] = implode(',', $value);
                    } elseif ($field['field_type'] == 'location') {
                        if (is_array($value)) {
                            foreach ($value as $key => $_val) {
                                $data[$field['id'] . '_' . $key] = $_val;
                            }
                        }
                    } elseif ($field['field_type'] == 'term_price') {
                        $data[$field['id']] = $value;
                        $valueObject = maybe_unserialize($value);

                        $taxonomy = $field['choices'];
                        $termRelation = new TermRelation();
                        $termRelation->deleteRelationByServiceID($postID, $taxonomy);

                        if (!empty($valueObject)) {
                            foreach ($valueObject as $termID => $termData) {
                                if ($termData['choose'] == 'yes') {
                                    $termRelation->createRelation($termID, $postID, 'car');
                                }
                            }
                        }
                    }
                }
            }
            if (!empty($data)) {
                if (isset($_POST['post_slug']) && (!isset($data['post_slug']) || empty($data['post_slug']))) {
                    $data['post_slug'] = Str::slug(esc_html(request()->get($post_title_field, 'new-car-' . time())));
                }
                if (isset($data['post_title'])) {
                    if (!isset($data['quantity'])) {
                        $data['quantity'] = 0;
                    }
                }
                $car->updateCar($data, $postID);
            }
            do_action('hh_saved_car_meta', $postID);
            do_action('hh_saved_service_meta', $postID);

            $respon = [
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Saved Successful')
            ];
            if ($step == 'finish' && !empty($redirect) && $event != 'tab') {

                $respon['redirect'] = dashboard_url($redirect);
            }
            $this->sendJson($respon, true);
        }
        $this->sendJson([
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Have error when saving data')
        ], true);
    }

    public function _duplicateCar(Request $request)
    {
        $car_id = request()->get('serviceID');
        $car_encrypt = request()->get('serviceEncrypt');

        if (!hh_compare_encrypt($car_id, $car_encrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('The car is not exist')
            ], true);
        }

        $car = new Car();
        $new_car = $car->duplicate($car_id);
        if ($new_car) {
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Duplicated new car successful'),
                'reload' => true
            ], true);
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Can not duplicate')
            ], true);
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

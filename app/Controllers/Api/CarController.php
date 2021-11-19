<?php

namespace App\Controllers\Api;

use App\Models\Booking;
use App\Models\Car;
use App\Models\CarPrice;
use App\Models\Taxonomy;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarController extends APIController
{
	private static $_inst;

	public function __construct() {
		$this->model = new Car();
	}

    public function getPriceRealtime($id, Request $request){
        if($id) {
            $rules = [
                'check_in' => 'required|string',
                'check_out' => 'required|string',
                'start_time' => 'required|string',
                'end_time' => 'required|string',
                'number' => 'required|integer'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendJson([
                    'status' => 0,
                    'message' => $validator->errors()
                ]);
            }

            $startDate = request()->get('check_in');
            $endDate = request()->get('check_out');
            $startTime = request()->get('start_time');
            $endTime = request()->get('end_time');
            $equipments = request()->get('equipment');
            $insurances = request()->get('insurance_plan');
            $number = (int)request()->get('number');

            $startDateTime = strtotime($startDate . ' ' . $startTime);
            $endDateTime = strtotime($endDate . ' ' . $endTime);
            if ($startDateTime < $endDateTime) {
                $priceData = \App\Controllers\Services\CarController::get_inst()->calcRealPrice($id, $startDate, $endDate, $startTime, $endTime, $equipments, $insurances, $number);
                $total = array_sum($priceData);
                return $this->sendJson([
                    'status' => 1,
                    'message' => __('Success'),
                    'price' => $total,
                    'price_html' => convert_price($total)
                ]);
            }
        }
        return $this->sendJson( [
            'status'  => 0,
            'message' => __('Data is invalid')
        ] );
    }

	public function addToCart(Request $request){
		$rules = [
			'post_id' => 'required|integer',
			'check_in' => 'required|string',
			'check_out' => 'required|string',
			'start_time' => 'required|string',
			'end_time' => 'required|string',
			'number' => 'required|integer'
		];

		$validator = Validator::make( $request->all(), $rules );
		if ( $validator->fails() ) {
			return $this->sendJson( [
				'status'  => 0,
				'message' => $validator->errors()
			] );
		}

		$user = get_user_by_access_token($request->bearerToken());

		$post_id = $request->post('post_id');
		$car_object = get_post($post_id, 'car');
		if($car_object && $user) {
			$request->request->add( [
				'carID'        => $post_id,
				'carEncrypt'   => hh_encrypt($post_id),
				'checkIn'       => $request->post( 'check_in' ),
				'checkOut'      => $request->post( 'check_out' ),
				'startTime'     => $request->post( 'start_time' ),
				'endTime'     => $request->post( 'end_time' ),
				'equipment' => $request->post( 'equipment' ),
				'insurance_plan' => $request->post( 'insurancePlan'),
				'number' => $request->post( 'number'),
			] );

			$result = \App\Controllers\Services\CarController::get_inst()->_addToCartCar($request, true);

			if(!$result['status']){
				return $this->sendJson( [
					'status'  => 0,
					'message' => $result['message']
				] );
			}else{
				update_user_meta($user->id, 'cart_data', json_encode($result['cart']));
				return $this->sendJson( [
					'status'  => 1,
					'message' => __('Success'),
					'cart' => $result['cart']
				] );
			}
		}
		return $this->sendJson( [
			'status'  => 0,
			'message' => __('Data is invalid')
		] );
	}

    public function getTimeAvailability($id, Request $request)
    {
        if (!empty($id)) {
            $rules = [
                'start_date' => 'required|string',
                'end_date' => 'required|string',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->sendJson([
                    'status' => 0,
                    'message' => $validator->errors()
                ]);
            }

            $start = request()->get('start_date');
            $end = request()->get('end_date');

            $carModel = new Car();
            $carObject = $carModel->getById($id);

            if ($start && $end && !is_null($carObject)) {
                $times = list_hours(15);
                $result = $times;
                if (!empty($carObject->quantity) && $carObject->quantity > 0) {
                    $bookingModel = new Booking();

                    $start = strtotime($start);
                    $end = strtotime($end);

                    $booked = $bookingModel->selectRaw("*")->where('service_id', $id)->whereRaw("status IN ('completed', 'incomplete')
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

                return $this->sendJson([
                    'status' => 1,
                    'message' => __('Success'),
                    'data' => $result
                ]);
            }
        }
        return $this->sendJson([
            'status' => 0,
            'message' => __('Data is invalid')
        ]);
    }

    public function getAvailability($id, Request $request){
        if(!empty($id)) {
            $rules = [
                'start_time' => 'required|string',
                'end_time' => 'required|string',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->sendJson([
                    'status' => 0,
                    'message' => $validator->errors()
                ]);
            }

            $events['events'] = [];
            $startTime = strtotime($request->post('start_time'));
            $endTime = strtotime($request->post('end_time'));
            if(!empty($startTime) && !empty($endTime)){
                $price_model = new CarPrice();
                $priceItems = $price_model->getPriceItems($id, $startTime, $endTime);
                $carObject = \App\Controllers\Services\CarController::get_inst()->getById($id);
                $price = (float)$carObject->base_price;
                $quatity = (int)$carObject->quantity;
                $bookingModel = new Booking();
                $bookingItems = $bookingModel->getBookingItems($id, $startTime, $endTime);
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
            return $this->sendJson([
                'status' => 1,
                'message' => __('Success'),
                'data' => $events
            ]);
        }
        return $this->sendJson([
            'status' => 0,
            'message' => __('Data is invalid')
        ]);
    }

    public function getFilters(Request $request){
        $lang = $request->get('lang', get_current_language());
        $price_range = get_car_min_max_price();
        $currency_symbol = current_currency('symbol');
        $symbol_position = current_currency('position');

        $terms = [];
        $filter_type = [
            'car-type' => __('Car Type'),
            'car-feature' => __('Car Features')
        ];

        $term = new Term();
        $tax = new Taxonomy();

        foreach ($filter_type as $k => $v) {
            $taxObject = $tax->getByName($k);
            $term_temp = [];
            if (!empty($taxObject) && is_object($taxObject)) {
                $terms_data = $term->getTerms($taxObject->taxonomy_id);
                if ($terms_data) {
                    foreach ($terms_data as $item) {
                        $term_temp[$item->term_id] = esc_attr(get_translate($item->term_title, $lang));
                    }
                }
            }

            $terms[$k] = [
                'label' => $v,
                'items' => $term_temp
            ];
        }

        $filters = [
            [
                'id' => 'price_range',
                'title' => __('Price'),
                'data' => [
                    'min' => $price_range['min'],
                    'max' => $price_range['max'],
                    'min_html' => convert_price($price_range['min']),
                    'max_html' => convert_price($price_range['max']),
                    'currency_symbol' => $currency_symbol,
                    'symbol_position' => $symbol_position
                ]
            ],
            [
                'id' => 'attributes',
                'title' => __('Attributes'),
                'data' => $terms
            ]
        ];

        return $this->sendJson([
            'status' => 1,
            'message' => __('Success'),
            'data' => $filters
        ]);
    }

	public function search(Request $request){
		$rules = [
			'page' => 'integer|min:1',
			'lat' => 'numeric',
			'lng' => 'numeric',
			'address' => 'string',
			'check_in' => 'date',
			'check_out' => 'date',
			'check_in_time' => 'string',
			'check_out_time' => 'string',
			'price_filter' => 'string',
			'car_type' => 'string',
			'car_feature' => 'string',
			'number' => 'integer|min:1'
		];
		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return $this->sendJson([
				'status' => 0,
				'message' => $validator->errors()
			]);
		}
		$data = parse_request($request, array_keys($rules));
		$data = $this->parseRequestParams($data, [
			'check_in' => 'checkIn',
			'check_out' => 'checkOut',
			'check_in_time' => 'checkInTime',
			'check_out_time' => 'checkOutTime',
			'car_type' => 'car-type',
			'car_feature' => 'car-feature'
		]);

        $posts = $this->model->getSearchResult($data);

        $results = [];
        if($posts['total'] > 0) {
            $lang = $request->get('lang', get_current_language());
            foreach ($posts['results'] as $k => $v) {
                $temp = $v;
                $temp->post_title = get_translate($v->post_title, $lang);
                $temp->post_content = get_translate($v->post_content, $lang);
                $temp->post_description = get_translate($v->post_description, $lang);
                $temp->location_address = get_translate($v->location_address, $lang);
                $temp->location_state = get_translate($v->location_state, $lang);
                $temp->location_country = get_translate($v->location_country, $lang);
                $temp->location_city = get_translate($v->location_city, $lang);
                $temp->cancellation_detail = get_translate($v->cancellation_detail, $lang);
                $temp->text_external_link = get_translate($v->text_external_link, $lang);
                $temp->gear_shift = get_translate($v->gear_shift, $lang);
                $temp->thumbnail_url = get_attachment_url($v->thumbnail_id);
                $temp->author_name = get_username($v->author);
                $temp->created_at = date(hh_date_format(), $v->created_at);
                $temp->equipments = maybe_unserialize($v->equipments);
                $temp->discount_by_day = maybe_unserialize($v->discount_by_day);
                $temp->insurance_plan = maybe_unserialize($v->insurance_plan);

                //Gallery
                $gallery = $v->gallery;
                $galleries = [];
                if(!empty($gallery)){
                    $gallery = explode(',', $gallery);
                    foreach ($gallery as $gk => $gv){
                        $galleries[$gk] = get_attachment_url($gv);
                    }
                }

                $temp->gallery = $galleries;

                $car_type = $v->car_type;
                if(!empty($car_type)){
                    $term_temp = get_term_by('id', $car_type);
                    if($term_temp) {
                        $temp->car_type = [
                            'id' => $car_type,
                            'link' => get_term_link($term_temp->term_name),
                            'name' => get_translate($term_temp->term_title, $lang)
                        ];
                    }else{
                        $temp->car_type = [];
                    }
                }

                $car_features = $v->features;
                $features = [];
                if(!empty($car_features)){
                    $car_features = explode(',', $car_features);
                    foreach ($car_features as $termk => $termv) {
                        $term_temp = get_term_by('id', $termv);
                        if($term_temp){
                            $features[] = [
                                'id' => $term_temp->term_id,
                                'link' => get_term_link($term_temp->term_name),
                                'name' => get_translate($term_temp->term_title, $lang)
                            ];
                        }

                    }
                }
                $temp->features = $features;

                $insurance_plan = $temp->insurance_plan;
                if(!empty($insurance_plan)){
                    foreach ($insurance_plan as $exk => $exv){
                        $extemp = $exv;
                        $extemp['name'] = get_translate($exv['name'], $lang);
                        $insurance_plan[$exk] = $extemp;
                    }
                    $temp->insurance_plan = $insurance_plan;
                }

                $results[] = $temp;
            }
        }

        return $this->sendJson([
            'status' => 1,
            'message' => __('Success'),
            'total' => $posts['total'],
            'results' => $results
        ]);
	}

    public function show($id, Request $request)
    {
        $lang = $request->get('lang', get_current_language());
        $data = $this->model->getById($id);
        if($data){
            $data->post_title = get_translate($data->post_title, $lang);
            $data->post_content = get_translate($data->post_content, $lang);
            $data->post_description = get_translate($data->post_description, $lang);
	        $data->thumbnail_url = get_attachment_url($data->thumbnail_id);
            $data->author_name = get_username($data->author);
	        $data->location_address = get_translate($data->location_address, $lang);
	        $data->location_state = get_translate($data->location_state, $lang);
	        $data->location_country = get_translate($data->location_country, $lang);
	        $data->location_city = get_translate($data->location_city, $lang);
	        $data->cancellation_detail = get_translate($data->cancellation_detail, $lang);
	        $data->text_external_link = get_translate($data->text_external_link, $lang);
            $data->gear_shift = get_translate($data->gear_shift, $lang);
            $data->base_price_html = convert_price($data->base_price);
            $data->created_at = date(hh_date_format(), $data->created_at);

            //Gallery
            $galleries = $data->gallery;
            $galleries_temp = [];
            if(!empty($galleries)){
                $galleries = explode(',', $galleries);
                foreach ($galleries as $k => $v){
                    $img = get_attachment($v);
                    if($img){
                        $galleries_temp[] = $img;
                    }
                }
            }
            $data->gallery = $galleries_temp;

            //Car Type
            $car_type = $data->car_type;
            if(!empty($car_type)){
                $term = get_term_by('id', $car_type);
                if($term){
                    $data->car_type = [
                        'id' => $term->term_id,
                        'link' => get_term_link($term->term_name),
                        'name' => get_translate($term->term_title, $lang)
                    ];
                }
            }

            //Features
            $features = $data->features;
            $features_temp = [];
            if(!empty($features)){
                $features = explode(',', $features);
                foreach ($features as $k => $v) {
                    $term = get_term_by('id', $v);
                    if($term) {
                        $features_temp[] = [
                            'id' => $term->term_id,
                            'link' => get_term_link($term->term_name),
                            'name' => get_translate($term->term_title, $lang)
                        ];
                    }
                }
            }

            $data->features = $features_temp;

            //Equipment
            if(!empty($data->equipments)){
                $equipments = maybe_unserialize($data->equipments);
                $equipments_temp = [];
                foreach ($equipments as $k => $v){
                    $term = get_term_by('id', $k);
                    if($term){
                        $equipments_temp[$k] = $v;
                        $equipments_temp[$k]['term'] = [
                            'id' => $term->term_id,
                            'link' => get_term_link($term->term_name),
                            'name' => get_translate($term->term_title, $lang),
                            'price_html' => convert_price($term->term_price),
                            'price' => $term->term_price
                        ];
                    }
                }
                $data->equipments = $equipments_temp;
            }

            //Discount by days
            if(!empty($data->discount_by_day)){
                $discount_by_day = maybe_unserialize($data->discount_by_day);
                $discount_by_day_temp = [];
                foreach ($discount_by_day as $k => $v){
                    $discount_by_day_temp[] = $v;
                    $discount_by_day_temp[$k]['name'] = get_translate($v['name'], $lang);
                }
                $data->discount_by_day = $discount_by_day_temp;
            }

            //Insurance Plan
            if(!empty($data->insurance_plan)){
                $insurance_plan = maybe_unserialize($data->insurance_plan);
                $insurance_plan_temp = [];
                foreach ($insurance_plan as $k => $v){
                    $insurance_plan_temp[] = $v;
                    $insurance_plan_temp[$k]['name'] = get_translate($v['name'], $lang);
                    $insurance_plan_temp[$k]['description'] = get_translate($v['description'], $lang);
                    $insurance_plan_temp[$k]['price_html'] = convert_price($v['price']);
                }
                $data->insurance_plan = $insurance_plan_temp;
            }

	        return $this->sendJson([
		        'status' => true,
		        'message' => __('Success'),
		        'data' => $data
	        ]);
        }
	    return $this->sendJson([
		    'status' => false,
		    'message' => __('Can not get data')
	    ]);
    }

    public static function inst(){
		if(empty(self::$_inst)){
			self::$_inst = new self();
		}
		return self::$_inst;
    }
}

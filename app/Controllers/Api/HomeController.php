<?php

namespace App\Controllers\Api;

use App\Models\Home;
use App\Models\HomeAvailability;
use App\Models\HomePrice;
use App\Models\Taxonomy;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends APIController
{
	private static $_inst;

	public function __construct() {
		$this->model = new Home();
	}

	public function getPriceRealtime($id, Request $request){
	    if($id) {
            $rules = [
                'check_in' => 'required|string',
                'check_out' => 'required|string'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendJson([
                    'status' => 0,
                    'message' => $validator->errors()
                ]);
            }

            $startDate = strtotime(request()->get('check_in'));
            $endDate = strtotime(request()->get('check_out'));
            $startTime = request()->get('start_time');
            $endTime = request()->get('end_time');
            $number_adults = (int)request()->get('number_adult');
            $number_children = (int)request()->get('number_children');
            $extraServices = request()->get('extra_services');

            $total = 0;
            $home = \App\Controllers\Services\HomeController::get_inst()->getById($id);
            if ($home->booking_type == 'per_night') {
                $numberNight = hh_date_diff($startDate, $endDate);
                $price = \App\Controllers\Services\HomeController::get_inst()->getRealPrice($home, $startDate, $endDate, $number_adults + $number_children);
                $requiredExtra = \App\Controllers\Services\HomeController::get_inst()->getRequiredExtraPrice($home, $numberNight);
                $extra = \App\Controllers\Services\HomeController::get_inst()->getExtraPrice($home, $extraServices, $numberNight);
                $total = $price + $requiredExtra + $extra;
            } elseif ($home->booking_type == 'per_hour') {
                if(empty($startTime)) {
                    return $this->sendJson([
                        'status' => 0,
                        'message' => __('Start time is required')
                    ]);
                }
                if(empty($endTime)){
                    return $this->sendJson([
                        'status' => 0,
                        'message' => __('End time is required')
                    ]);
                }

                $startTime = strtotime(date('Y-m-d', $startDate) . ' ' . $startTime);
                $endTime = strtotime(date('Y-m-d', $endDate) . ' ' . $endTime);
                if (is_timestamp($startTime) && is_timestamp($endTime) && $startTime < $endTime) {
                    $numberNight = hh_date_diff($startTime, $endTime, 'hour');
                    $price = \App\Controllers\Services\HomeController::get_inst()->getRealPriceByTime($home, $startTime, $endTime, $number_adults + $number_children);
                    $requiredExtra = \App\Controllers\Services\HomeController::get_inst()->getRequiredExtraPrice($home, $numberNight);
                    $extra = \App\Controllers\Services\HomeController::get_inst()->getExtraPrice($home, $extraServices, $numberNight);
                    $total = $price + $requiredExtra + $extra;
                } else {
                    return $this->sendJson( [
                        'status'  => 0,
                        'message' => __('Data is invalid')
                    ] );
                }
            };
            return $this->sendJson([
                'status' => 1,
                'message' => __('Success'),
                'price' => $total,
                'price_html' => convert_price($total)
            ]);
        }
        return $this->sendJson( [
            'status'  => 0,
            'message' => __('Data is invalid')
        ] );
    }

	public function addToCart(Request $request){
		$rules = [
			'post_id' => 'required|integer',
			'number_adult' => 'required|integer',
			'number_children' => 'integer',
			'number_infant' => 'integer',
			'check_in' => 'required|string',
			'check_out' => 'required|string'
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
		$home_object = get_post($post_id, 'home');
		if($home_object && $user) {
			$request->request->add( [
				'homeID'        => $post_id,
				'homeEncrypt'   => hh_encrypt($post_id),
				'num_adults'    => $request->post( 'number_adult' ),
				'num_children'  => $request->post( 'number_children' ),
				'num_infants'   => $request->post( 'number_infant' ),
				'checkIn'       => $request->post( 'check_in' ),
				'checkOut'      => $request->post( 'check_out' ),
				'startTime'     => $request->post( 'start_time' ),
				'endTime'       => $request->post( 'end_time' ),
				'extraServices' => $request->post( 'extra_service' ),
			] );

			$result = \App\Controllers\Services\HomeController::get_inst()->_addToCartHome($request, true);

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

    public function getTimeAvailability($id, Request $request){
        if(!empty($id)) {
            $rules = [
                'date' => 'required|string'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->sendJson([
                    'status' => 0,
                    'message' => $validator->errors()
                ]);
            }

            $date = request()->get('date');

            if ($date) {
                $home = get_post($id, 'home');
                $start_time = (!empty($home->checkin_time)) ? $home->checkin_time : '12:00 AM';
                $end_time = (!empty($home->checkout_time)) ? $home->checkout_time : '11:30 PM';
                $start_date = strtotime($date . ' ' . $start_time);
                $end_date = strtotime($date . ' ' . $end_time);
                $avai_model = new HomeAvailability();
                $calendarItems = $avai_model->getAvailabilityItems($id, strtotime($date), strtotime($date));
                $times = list_hours(30);
                $result = $times;
                foreach ($times as $key => $time) {
                    $timestamp = strtotime($date . ' ' . $key);
                    if ($timestamp < $start_date || $timestamp > $end_date) {
                        unset($result[$key]);
                        continue;
                    }
                    foreach ($calendarItems['results'] as $item) {
                        if ($timestamp >= $item->start_time && $timestamp <= $item->end_time) {
                            unset($result[$key]);
                            break;
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
                $price_model = new HomePrice();
                $avai_model = new HomeAvailability();

                $priceItems = $price_model->getPriceItems($id, $startTime, $endTime, $status = 'on');
                $homeObject = \App\Controllers\Services\HomeController::get_inst()->getById($id);

                $price = (float)$homeObject->base_price;
                $wprice = $homeObject->weekend_price;
                $ruleWeekend = $homeObject->weekend_to_apply;
                if ($homeObject->booking_type == 'per_night') {
                    $endTime = strtotime('-1 day', $endTime);
                    $avaiItems = $avai_model->getAvailabilityItems($id, $startTime, $endTime);
                    for ($i = $startTime; $i <= $endTime; $i = strtotime('+1 day', $i)) {
                        $status = 'available';
                        $event = convert_price($price);
                        $inCustom = false;
                        foreach ($avaiItems['results'] as $avaiItem) {
                            if ($i >= $avaiItem->start_date && $i <= $avaiItem->end_date) {
                                if ($avaiItem->booking_id == 0 && $avaiItem->total_minutes == 1440) {
                                    $status = 'not_available';
                                    $event = 'Unavailable';
                                    break;
                                } else {
                                    $status = 'booked';
                                    $event = __('Booked');
                                    break;
                                }
                            }
                        }
                        if ($status == 'available') {
                            foreach ($priceItems['results'] as $range) {
                                if ($i >= $range->start_time && $i <= $range->end_time) {
                                    $event = convert_price($range->price);
                                    $inCustom = true;
                                    break;
                                }
                            }
                        }

                        if (!$inCustom) {
                            if (!is_null($wprice) && is_weekend($i, $ruleWeekend)) {
                                $event = convert_price($wprice);
                            }
                        }
                        $events['events'][] = [
                            'start' => date('Y-m-d', $i),
                            'end' => date('Y-m-d', $i),
                            'status' => $status,
                            'event' => $event
                        ];
                    }
                } elseif ($homeObject->booking_type == 'per_hour') {
                    $avaiItems = $avai_model->getAvailabilityTimeItems($id, $startTime, $endTime);
                    $date = date('Y-m-d');
                    $start_time = (!empty($homeObject->checkin_time)) ? $homeObject->checkin_time : '12:00 AM';
                    $end_time = (!empty($homeObject->checkout_time)) ? $homeObject->checkout_time : '11:30 PM';
                    $start_date = strtotime($date . ' ' . $start_time);
                    $end_date = strtotime($date . ' ' . $end_time);
                    $range_time = hh_date_diff($start_date, $end_date, 'minute');
                    for ($i = $startTime; $i <= $endTime; $i = strtotime('+1 day', $i)) {
                        $status = 'available';

                        $event = convert_price($price);
                        $inCustom = false;
                        foreach ($avaiItems['results'] as $item) {
                            if ($i >= $item->start_date && $i <= $item->end_date) {
                                if ((int)$item->total >= $range_time) {
                                    $status = ($item->has_booking > 0) ? 'booked' : 'not_available';
                                    break;
                                }
                            }
                        }
                        if ($status == 'available') {
                            foreach ($priceItems['results'] as $range) {
                                if ($i >= $range->start_time && $i <= $range->end_time) {
                                    $event = convert_price($range->price);
                                    $inCustom = true;
                                    break;
                                }
                            }
                            if (!$inCustom) {
                                if (!empty($wprice) && is_weekend($i, $ruleWeekend)) {
                                    $event = convert_price($wprice);
                                }
                            }
                        } elseif ($status == 'booked') {
                            $event = __('Booked');
                        } else {
                            $event = __('Unavailable');
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
        }
        return $this->sendJson([
            'status' => 0,
            'message' => __('Data is invalid')
        ]);
    }

	public function getFilters(Request $request){
        $lang = $request->get('lang', get_current_language());
        $price_range = \App\Controllers\Services\HomeController::get_inst()->getMinMaxPrice();
        $currency_symbol = current_currency('symbol');
        $symbol_position = current_currency('position');

        $terms = [];
        $filter_type = [
            'home-type' => __('Home Type'),
            'home-amenity' => __('Home Amenity'),
            'home-facilities' => __('Home Facilities Fields')
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
			'start_time' => 'string',
			'end_time' => 'string',
			'booking_type' => 'in:per_night,per_hour',
			'num_adults' => 'integer|min:1',
			'num_children' => 'integer',
			'num_infants' => 'integer',
			'price_filter' => 'string',
			'home_type' => 'string',
			'home_amenity' => 'string',
            'home_facilities' => 'string',
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
			'start_time' => 'startTime',
			'end_time' => 'endTime',
			'booking_type' => 'bookingType',
			'home_type' => 'home-type',
			'home_amenity' => 'home-amenity',
            'home_facilities' => 'home-facilities'
		]);

		$posts = $this->model->getSearchResult($data);
		$results = [];
		if($posts['total'] > 0){
			$lang = $request->get('lang', get_current_language());
			foreach ($posts['results'] as $k => $v){
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
				$temp->thumbnail_url = get_attachment_url($v->thumbnail_id);
				$temp->author_name = get_username($v->author);
				$temp->created_at = date(hh_date_format(), $v->created_at);
				$temp->extra_services = maybe_unserialize($v->extra_services);
				$temp->import_ical_url = maybe_unserialize($v->import_ical_url);

				$extra_services = $temp->extra_services;
				if(!empty($extra_services)){
                    foreach ($extra_services as $exk => $exv){
                        $extemp = $exv;
                        $extemp['name'] = get_translate($exv['name'], $lang);
                        $extra_services[$exk] = $extemp;
                    }
                    $temp->extra_services = $extra_services;
                }

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

				//Post categories
				$home_amenities = $v->amenities;
				$amenities = [];
				if(!empty($home_amenities)){
					$home_amenities = explode(',', $home_amenities);
					foreach ($home_amenities as $termk => $termv) {
						$term_temp = get_term_by('id', $termv);
						if($term_temp){
							$amenities[] = [
								'id' => $term_temp->term_id,
								'link' => get_term_link($term_temp->term_name),
								'name' => get_translate($term_temp->term_title, $lang)
							];
						}

					}
				}
				$temp->amenities = $amenities;

				$home_type = $v->home_type;
				if(!empty($home_type)){
					$term_temp = get_term_by('id', $home_type);
					if($term_temp) {
						$temp->home_type = [
							'id' => $home_type,
							'link' => get_term_link($term_temp->term_name),
							'name' => get_translate($term_temp->term_title, $lang)
						];
					}else{
						$temp->home_type = [];
					}
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
            $data->created_at = date(hh_date_format(), $data->created_at);
	        $data->location_address = get_translate($data->location_address, $lang);
	        $data->location_state = get_translate($data->location_state, $lang);
	        $data->location_country = get_translate($data->location_country, $lang);
	        $data->location_city = get_translate($data->location_city, $lang);
	        $data->cancellation_detail = get_translate($data->cancellation_detail, $lang);
	        $data->text_external_link = get_translate($data->text_external_link, $lang);

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

	        //Extra services
            if(!empty($data->extra_services)){
                $extras = maybe_unserialize($data->extra_services);
                $extras_temp = [];
                foreach ($extras as $k => $v){
                    $extras_temp[] = $v;
                    $extras_temp[$k]['name'] = get_translate($v['name'], $lang);
                }
                $data->extra_services = $extras_temp;
            }

            //Home Amenities
            $home_amenities = $data->amenities;
            $amenities = [];
            if(!empty($home_amenities)){
                $home_amenities = explode(',', $home_amenities);
                foreach ($home_amenities as $k => $v) {
                    $term = get_term_by('id', $v);
                    if($term) {
                        $amenities[] = [
                            'id' => $term->term_id,
                            'link' => get_term_link($term->term_name),
                            'name' => get_translate($term->term_title, $lang)
                        ];
                    }
                }
            }

            $data->amenities = $amenities;

            //Home Type
            $home_type = $data->home_type;
            if(!empty($home_type)){
                $term = get_term_by('id', $home_type);
                if($term){
                    $data->home_type = [
                        'id' => $term->term_id,
                        'link' => get_term_link($term->term_name),
                        'name' => get_translate($term->term_title, $lang)
                    ];
                }
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

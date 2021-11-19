<?php

namespace App\Controllers\Api;

use App\Models\Experience;
use App\Models\ExperienceAvailability;
use App\Models\ExperiencePrice;
use App\Models\Taxonomy;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExperienceController extends APIController
{
	private static $_inst;

	public function __construct() {
		$this->model = new Experience();
	}

    public function getPriceRealtime($id, Request $request){
        if($id) {
            $bookingType = request()->get('booking_type', 'date_time');
            if ($bookingType == 'just_date' || $bookingType == 'package') {
                $rules = [
                    'check_in' => 'required|string'
                ];
            }else{
                $rules = [
                    'start_time' => 'required|string'
                ];
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendJson([
                    'status' => 0,
                    'message' => $validator->errors()
                ]);
            }

            $bookingType = request()->get('booking_type', 'date_time');
            $startTime = request()->get('start_time');
            $extraServices = request()->get('extra_services');
            $adult_number = (int)request()->get('number_adult', 0);
            $child_number = (int)request()->get('number_children', 0);
            $infant_number = (int)request()->get('number_infant', 0);
            $package_name = request()->get('tour_package');
            if ($bookingType == 'just_date' || $bookingType == 'package') {
                $startTime = strtotime(request()->get('check_in'));
            }

            $total = 0;
            $prices = [];
            $experience = \App\Controllers\Services\ExperienceController::get_inst()->getById($id);
            if ($bookingType == 'package') {
                $package = \App\Controllers\Services\ExperienceController::get_inst()->getPackageByName($experience, $package_name);
                if (!empty($package)) {
                    $base_price = (float)$package['price'];
                    $sale_price = $package['sale_price'];
                    if (!empty($sale_price) && (float)$sale_price < $base_price) {
                        $base_price = $sale_price;
                    }
                    $requiredExtra = \App\Controllers\Services\ExperienceController::get_inst()->getRequiredExtraPrice($experience, 1);
                    $extra = \App\Controllers\Services\ExperienceController::get_inst()->getExtraPrice($experience, $extraServices, 1);
                    $total = $base_price + $requiredExtra + $extra;
                }
            } else {
                $price_categories = $experience->price_categories;
                $prices = \App\Controllers\Services\ExperienceController::get_inst()->getRealPrice($startTime, $adult_number, $child_number, $infant_number, $experience);
                $requiredExtra = \App\Controllers\Services\ExperienceController::get_inst()->getRequiredExtraPrice($experience, 1);
                $extra = \App\Controllers\Services\ExperienceController::get_inst()->getExtraPrice($experience, $extraServices, 1);
                $total = $prices['total'] + $requiredExtra + $extra;
                if (in_array('enable_adults', $price_categories)) {
                    $prices['adult_html'] = convert_price($prices['adult']);
                }
                if (in_array('enable_children', $price_categories)) {
                    $prices['child_html'] = convert_price($prices['child']);
                }
                if (in_array('enable_infants', $price_categories)) {
                    $prices['infant_html'] = convert_price($prices['infant']);
                }
            }
            return $this->sendJson([
                'status' => 1,
                'message' => __('Success'),
                'total' => $total,
                'total_html' => convert_price($total),
                'prices' => $prices
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
		$experience_object = get_post($post_id, 'experience');
		if($experience_object && $user) {
			$request->request->add( [
				'experienceID'        => $post_id,
				'experienceEncrypt'   => hh_encrypt($post_id),
				'num_adults'    => $request->post( 'number_adult' ),
				'num_children'  => $request->post( 'number_children' ),
				'num_infants'   => $request->post( 'number_infant' ),
				'checkIn'       => $request->post( 'check_in' ),
				'checkOut'      => $request->post( 'check_out' ),
				'startTime'     => $request->post( 'start_time' ),
				'extraServices' => $request->post( 'extra_service' ),
				'bookingType' => $request->post( 'booking_type'),
				'tour_package' => $request->post( 'tour_package'),
			] );

			$result = \App\Controllers\Services\ExperienceController::get_inst()->_addToCartExperience($request, true);

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
                $price_model = new ExperiencePrice();
                $priceItems = $price_model->getPriceItems($id, $startTime, $endTime, 'just_date');
                $avai_model = new ExperienceAvailability();
                $avai_items = $avai_model->getAvailabilityItems($id, $startTime, $endTime);
                $today = strtotime(date('Y-m-d'));
                for ($i = $startTime; $i <= $endTime; $i = strtotime('+1 day', $i)) {
                    if ($i < $today) {
                        $events['events'][] = [
                            'start' => date('Y-m-d', $i),
                            'end' => date('Y-m-d', $i),
                            'status' => 'not_available',
                            'event' => __('Unavailable')
                        ];
                        continue;
                    }
                    $in_avai = false;
                    foreach ($avai_items['results'] as $avai_item) {
                        if ($i == $avai_item->date) {
                            $events['events'][] = [
                                'start' => date('Y-m-d', $i),
                                'end' => date('Y-m-d', $i),
                                'status' => 'not_available',
                                'event' => __('Unavailable')
                            ];
                            $in_avai = true;
                            break;
                        }
                    }
                    if ($in_avai) {
                        continue;
                    }
                    $in_date = false;
                    foreach ($priceItems['results'] as $item) {
                        if ($i == $item->start_date) {
                            $in_date = true;
                            $events['events'][] = [
                                'start' => date('Y-m-d', $item->start_date),
                                'end' => date('Y-m-d', $item->end_date),
                                'status' => 'available',
                                'event' => __('Available')
                            ];
                            break;
                        }
                    }
                    if (!$in_date) {
                        $events['events'][] = [
                            'start' => date('Y-m-d', $i),
                            'end' => date('Y-m-d', $i),
                            'status' => 'not_available',
                            'event' => __('Unavailable')
                        ];
                    }
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
        $price_range = \App\Controllers\Services\ExperienceController::get_inst()->getMinMaxPrice();
        $currency_symbol = current_currency('symbol');
        $symbol_position = current_currency('position');

        $terms = [];
        $filter_type = [
            'experience-languages' => __('Experience Languages'),
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

        $experience_types = get_terms('experience-type', true);
        $types = [];
        if(!$experience_types->isEmpty()){
            foreach($experience_types as $term){
                $types[$term->term_id]['thumbnail'] = get_attachment_url($term->term_image, [100, 60]);
                $types[$term->term_id]['name'] = get_translate($term->term_title, $lang);
            }
        }

        $terms['experience-type'] = [
            'label' => __('Types'),
            'items' => $types
        ];

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
			'num_adults' => 'integer|min:1',
			'num_children' => 'integer',
			'num_infants' => 'integer',
			'price_filter' => 'string',
			'experience_type' => 'string',
			'experience_languages' => 'string',
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
			'experience_type' => 'experience-type',
			'experience_languages' => 'experience-languages'
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
                $temp->durations = get_translate($v->durations, $lang);
                $temp->thumbnail_url = get_attachment_url($v->thumbnail_id);
                $temp->author_name = get_username($v->author);
                $temp->created_at = date(hh_date_format(), $v->created_at);
                $temp->extra_services = maybe_unserialize($v->extra_services);
                $temp->itinerary = maybe_unserialize($v->itinerary);
                $temp->tour_packages = maybe_unserialize($v->tour_packages);
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

                $ex_languages = $v->languages;
                $languages = [];
                if(!empty($ex_languages)){
                    $ex_languages = explode(',', $ex_languages);
                    foreach ($ex_languages as $termk => $termv) {
                        $term_temp = get_term_by('id', $termv);
                        if($term_temp){
                            $languages[] = [
                                'id' => $term_temp->term_id,
                                'link' => get_term_link($term_temp->term_name),
                                'name' => get_translate($term_temp->term_title, $lang)
                            ];
                        }

                    }
                }
                $temp->languages = $languages;

                $ex_inclusions = $v->inclusions;
                $inclusions = [];
                if(!empty($ex_inclusions)){
                    $ex_inclusions = explode(',', $ex_inclusions);
                    foreach ($ex_inclusions as $termk => $termv) {
                        $term_temp = get_term_by('id', $termv);
                        if($term_temp){
                            $inclusions[] = [
                                'id' => $term_temp->term_id,
                                'link' => get_term_link($term_temp->term_name),
                                'name' => get_translate($term_temp->term_title, $lang)
                            ];
                        }

                    }
                }
                $temp->inclusions = $inclusions;

                $ex_exclusions = $v->exclusions;
                $exclusions = [];
                if(!empty($ex_exclusions)){
                    $ex_exclusions = explode(',', $ex_exclusions);
                    foreach ($ex_exclusions as $termk => $termv) {
                        $term_temp = get_term_by('id', $termv);
                        if($term_temp){
                            $exclusions[] = [
                                'id' => $term_temp->term_id,
                                'link' => get_term_link($term_temp->term_name),
                                'name' => get_translate($term_temp->term_title, $lang)
                            ];
                        }

                    }
                }
                $temp->exclusions = $exclusions;

                $itinerary = $temp->itinerary;
                if(!empty($itinerary)){
                    foreach ($itinerary as $exk => $exv){
                        $extemp = $exv;
                        $extemp['sub_title'] = get_translate($exv['sub_title'], $lang);
                        $extemp['title'] = get_translate($exv['title'], $lang);
                        $extemp['description'] = get_translate($exv['description'], $lang);
                        $extemp['image'] = get_attachment_url($exv['image']);
                        $itinerary[$exk] = $extemp;
                    }
                    $temp->itinerary = $itinerary;
                }

                $experience_type = $v->experience_type;
                if(!empty($experience_type)){
                    $term_temp = get_term_by('id', $experience_type);
                    if($term_temp) {
                        $temp->experience_type = [
                            'id' => $experience_type,
                            'link' => get_term_link($term_temp->term_name),
                            'name' => get_translate($term_temp->term_title, $lang)
                        ];
                    }else{
                        $temp->experience_type = [];
                    }
                }

                $tour_packages = $temp->tour_packages;
                if(!empty($tour_packages)){
                    foreach ($tour_packages as $exk => $exv){
                        $extemp = $exv;
                        $extemp['title'] = get_translate($exv['title'], $lang);
                        $extemp['detail'] = get_translate($exv['detail'], $lang);
                        $tour_packages[$exk] = $extemp;
                    }
                    $temp->tour_packages = $tour_packages;
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
            $data->durations = get_translate($data->durations, $lang);
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

	        //Itinerary services
            if(!empty($data->itinerary)){
                $itinerary = maybe_unserialize($data->itinerary);
                $itinerary_temp = [];
                foreach ($itinerary as $k => $v){
                    $itinerary_temp[] = $v;
                    $itinerary_temp[$k]['sub_title'] = get_translate($v['sub_title'], $lang);
                    $itinerary_temp[$k]['title'] = get_translate($v['title'], $lang);
                    $itinerary_temp[$k]['description'] = get_translate($v['description'], $lang);
                }
                $data->itinerary = $itinerary_temp;
            }

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

            //Languages
            $languages = $data->languages;
            $languages_temp = [];
            if(!empty($languages)){
                $languages = explode(',', $languages);
                foreach ($languages as $k => $v) {
                    $term = get_term_by('id', $v);
                    if($term) {
                        $languages_temp[] = [
                            'id' => $term->term_id,
                            'link' => get_term_link($term->term_name),
                            'name' => get_translate($term->term_title, $lang)
                        ];
                    }
                }
            }

            $data->languages = $languages_temp;

            //Inclusions
            $inclusions = $data->inclusions;
            $inclusions_temp = [];
            if(!empty($inclusions)){
                $inclusions = explode(',', $inclusions);
                foreach ($inclusions as $k => $v) {
                    $term = get_term_by('id', $v);
                    if($term) {
                        $inclusions_temp[] = [
                            'id' => $term->term_id,
                            'link' => get_term_link($term->term_name),
                            'name' => get_translate($term->term_title, $lang)
                        ];
                    }
                }
            }

            $data->inclusions = $inclusions_temp;

            //Exclusions
            $exclusions = $data->exclusions;
            $exclusions_temp = [];
            if(!empty($exclusions)){
                $exclusions = explode(',', $exclusions);
                foreach ($exclusions as $k => $v) {
                    $term = get_term_by('id', $v);
                    if($term) {
                        $exclusions_temp[] = [
                            'id' => $term->term_id,
                            'link' => get_term_link($term->term_name),
                            'name' => get_translate($term->term_title, $lang)
                        ];
                    }
                }
            }

            $data->exclusions = $exclusions_temp;

            //Experience Type
            $experience_type = $data->experience_type;
            if(!empty($experience_type)){
                $term = get_term_by('id', $experience_type);
                if($term){
                    $data->experience_type = [
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

<?php

namespace App\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Comment;
use App\Models\Experience;
use App\Models\ExperienceAvailability;
use App\Models\ExperienceBooking;
use App\Models\ExperiencePrice;
use App\Models\TermRelation;
use ICal\ICal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Sentinel;

class ExperienceController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        add_action('hh_dashboard_breadcrumb', [$this, '_addCreateExperienceButton']);
    }

    public function assignDataByUserID($user_id, $user_assign)
    {
        $model = new Experience();
        $model->updateAuthor(['author' => $user_assign], $user_id);
    }

    public function deleteDataByUserID($user_id)
    {
        $model = new Experience();
        $allExperience = $model->getAllExperiences([
            'author' => $user_id,
            'number' => -1
        ]);
        if ($allExperience['total'] > 0) {
            foreach ($allExperience['results'] as $item) {
                $model->deleteExperienceItem($item->post_id);
            }
        }
    }

    public function _bulkActions(Request $request)
    {
        $action = request()->get('action', '');
        $post_id = request()->get('post_id', '');

        if (!empty($action) && !empty($post_id)) {
            $post_id = explode(',', $post_id);
            $experienceModel = new Experience();
            switch ($action) {
                case 'delete':
                    $experienceModel->whereIn('post_id', $post_id)->delete();
                    $commentModel = new Comment();
                    $commentModel->whereIn('post_id', $post_id)->where('post_type', 'experience')->delete();
                    $termRelationModel = new TermRelation();
                    $termRelationModel->whereIn('service_id', $post_id)->delete();
                    $experiencePriceModel = new ExperiencePrice();
                    $experiencePriceModel->whereIn('experience_id', $post_id)->delete();
                    $experienceAvailabilityModel = new ExperienceAvailability();
                    $experienceAvailabilityModel->whereIn('experience_id', $post_id)->delete();
                    break;
                case 'publish':
                case 'pending':
                case 'draft':
                case 'trash':
                    $experienceModel->updateMultiExperience([
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

    public function _importIcal()
    {
        $experience_model = new Experience();
        $posts = $experience_model->getAllIcalItems();
        if (is_object($posts)) {
            foreach ($posts as $post) {
                $experience_id = $post->post_id;
                $icalList = maybe_unserialize($post->import_ical_url);
                if (is_array($icalList)) {
                    foreach ($icalList as $item) {
                        $url = $item['ical_url'];
                        try {
                            $ical = new ICal($url, [
                                'defaultTimeZone' => get_timezone(),
                            ]);
                            if ($ical->hasEvents()) {
                                $experience_avai = new ExperienceAvailability();
                                $today = strtotime(date('Y-m-d'));
                                $events = $ical->events();
                                foreach ($events as $event) {
                                    if (!empty($event->dtstart_tz) && !empty($event->dtend_tz)) {
                                        $dtstart = $ical->iCalDateToDateTime($event->dtstart_tz)->format('U');
                                        $dtend = $ical->iCalDateToDateTime($event->dtend_tz)->format('U');
                                    } else {
                                        $dtstart = $ical->iCalDateToDateTime($event->dtstart)->format('U');
                                        $dtend = $ical->iCalDateToDateTime($event->dtend)->format('U');
                                    }
                                    if ($dtstart >= $today) {
                                        $dtend = strtotime('-1 day', $dtend);
                                        for ($i = $dtstart; $i <= $dtend; $i = strtotime('+1 day', $i)) {
                                            $start = strtotime(date('Y-m-d', $i));
                                            $hasAvai = $experience_avai->getAvailabilityItem($experience_id, $start);
                                            if (!$hasAvai) {
                                                $data = [
                                                    'experience_id' => $experience_id,
                                                    'type' => 'ical',
                                                    'summary' => !empty($event->summary) ? $event->summary : __('Unavailable'),
                                                    'date' => $start,
                                                ];
                                                $experience_avai->createAvailabilityItem($data);
                                            }
                                        }

                                    }
                                }
                            }
                        } catch (Exception $e) {

                        }
                    }
                }
            }
        }
    }

    public function _getIcalUrl(Request $request, $experience_id)
    {
        $experience_avai = new ExperienceAvailability();
        $totay = strtotime(date('Y-m-d'));
        $allItems = $experience_avai->getAvailabilityItems($experience_id, $totay);
        date_default_timezone_set(get_timezone());
        $vCalendar = new \Eluceo\iCal\Component\Calendar(url('/'));
        if ($allItems['total']) {
            foreach ($allItems['results'] as $item) {
                if (empty($item->type)) {
                    $vEvent = new \Eluceo\iCal\Component\Event();

                    $vEvent->setDtStart(new \DateTime(date('Y-m-d', $item->date)))
                        ->setDtEnd(new \DateTime(date('Y-m-d', $item->date)))
                        ->setNoTime(true);
                    $vEvent->setSummary(__('Unavailable'));

                    $vCalendar->addComponent($vEvent);
                }
            }
        }

        @ob_clean();
        $file_name = 'ical-' . post_type_info('experience')['slug'] . '-' . $experience_id;
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . trim($file_name) . '.ics"');
        echo esc_text($vCalendar->render());
        exit();
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
        $post = get_post($post_id, 'experience');
        $admin = get_admin_user();
        $partner = get_user_by_id($post->author);

        send_mail(esc_html($email), esc_html($name), $admin->email, sprintf(__('[%s] Have a booking request from [Experience ID: %s] - %s'), get_option('site_name'), $post_id, $post->post_title), balanceTags($message));
        send_mail(esc_html($email), esc_html($name), $partner->email, sprintf(__('[%s] Have a booking request from [Experience ID: %s] - %s'), get_option('site_name'), $post_id, $post->post_title), balanceTags($message));
        return $this->sendJson([
            'status' => 1,
            'message' => view('common.alert', ['type' => 'success', 'message' => __('Sent! Please wait for a response from the partner')])->render()
        ]);
    }

    public function _addCreateExperienceButton()
    {
        $screen = current_screen();
        if ($screen == 'my-experience') {
            echo view("dashboard.components.services.experience.quick-add-experience")->render();
        }
    }

    public function listOfExperiences($data = [])
    {
        $experience = new Experience();
        return $experience->listOfExperiences($data);
    }

    public function _getSearchResult()
    {

        $experience = new Experience();
        $post_data = request()->all();
        $data = $experience->getSearchResult($post_data);
        $search_string = view('frontend.experience.search.search_string', [
            'count' => $data['total'],
            'address' => request()->get('address'),
            'check_in' => request()->get('checkIn'),
            'check_out' => request()->get('checkOut')
        ])->render();

        if (isset($post_data['_token'])) {
            unset($post_data['_token']);
        }

        $url_temp = http_build_query($post_data);

        $html = '';
        $locations = [];
        if ($data['total'] > 0) {
            $column = (!isset($post_data['showmap']) || (isset($post_data['showmap']) && $post_data['showmap'] == 'yes')) ? 'col-12 col-sm-6 col-md-4' : 'col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2';
            foreach ($data['results'] as $k => $item) {
                $item = $this->setup_post_data($item);
                $html .= '<div class="' . esc_attr($column) . '">';
                $html .= view('frontend.experience.loop.grid', ['item' => $item])->render();
                $html .= '</div>';

                $locations[] = [
                    'lat' => $item->location_lat,
                    'lng' => $item->location_lng,
                    'price' => convert_price($item->base_price),
                    'post_id' => $item->post_id,
                    'title' => get_translate($item->post_title),
                    'url' => get_experience_permalink($item->post_id, $item->post_slug),
                    'thumbnail' => get_attachment_url($item->thumbnail_id, [75, 75])
                ];
            }
        }else{

            $lat = request()->get('lat', 0);
            $lng = request()->get('lng', 0);
            $locations[0] = [
                'lat' => $lat,
                'lng' => $lng
            ];
        }

        $pag = view('frontend.experience.search.search_pag', [
            'total' => $data['total'],
            'query_string' => '?' . $url_temp,
            'current_url' => $post_data['current_url'],
            'page' => $post_data['page'],
            'number' => isset($post_data['number']) ? intval($post_data['number']) : posts_per_page()
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
        return view("frontend.experience.search.search", ['page' => $page]);
    }

    public function _updateExperienceAvailability($booking_id, $status)
    {
        $booking = get_booking($booking_id, 'experience');
        if (is_object($booking)) {
            $price_model = new ExperiencePrice();
            $price_items = $price_model->getPriceItems($booking->service_id, $booking->start_date, $booking->start_date, 'just_date');

            $booking_model = new Booking();
            $booking_items = $booking_model->getBookingItems($booking->service_id, $booking->start_date, $booking->start_date, false, 'experience');
            if ($booking_items['total'] <= 0 || $price_items['total'] <= 0) {
                $experience_avai_model = new ExperienceAvailability();
                $old_avai = $experience_avai_model->getAvailabilityItem($booking->service_id, $booking->start_date);
                if (is_object($old_avai)) {
                    $experience_avai_model->deleteAvailabilityItem($old_avai->experience_id, $old_avai->date);
                }
            } else {
                $service_data = get_booking_data($booking_id, 'serviceObject');
                if ($service_data->booking_type == 'package') {
                    $experience_avai_model = new ExperienceAvailability();
                    $old_avai = $experience_avai_model->getAvailabilityItem($booking->service_id, $booking->start_date);
                    if (is_null($old_avai)) {
                        $experience_avai_model->createAvailabilityItem([
                            'experience_id' => $booking->service_id,
                            'date' => $booking->start_date
                        ]);
                    }
                } else {
                    if ($price_items['total']) {
                        $slot_not_available = 0;
                        $slot = $price_items['total'];
                        foreach ($price_items['results'] as $price_item) {
                            $max_people = (int)$price_item->max_people;
                            $total_people = 0;
                            foreach ($booking_items['results'] as $booking_item) {
                                if ($booking_item->start_time == $price_item->start_time) {
                                    $total_people += (int)$booking_item->number_of_guest;
                                }
                            }
                            if ($max_people <= $total_people) {
                                $slot_not_available += 1;
                            }
                        }
                        $experience_avai_model = new ExperienceAvailability();
                        $old_avai = $experience_avai_model->getAvailabilityItem($booking->service_id, $booking->start_date);
                        if ($slot == $slot_not_available) {
                            if (is_null($old_avai)) {
                                $experience_avai_model->createAvailabilityItem([
                                    'experience_id' => $booking->service_id,
                                    'date' => $booking->start_date
                                ]);
                            }
                        } else {
                            if (is_object($old_avai)) {
                                $experience_avai_model->deleteAvailabilityItem($old_avai->experience_id, $old_avai->date);
                            }
                        }
                    }
                }
            }
        }
    }

    public function getMinMaxPrice()
    {
        $experience_model = new Experience();
        $minMaxPrice = $experience_model->getMinMaxPrice();
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

    public function _addToCartExperience(Request $request, $api = false)
    {
        $experienceID = (int)request()->get('experienceID');
        $experienceEncrypt = request()->get('experienceEncrypt');

        $number_adult = (int)request()->get('num_adults', 0);
        $number_child = (int)request()->get('num_children', 0);
        $number_infant = (int)request()->get('num_infants', 0);
        $startDate = strtotime(request()->get('checkIn'));
        $endDate = strtotime(request()->get('checkOut'));
        $startTime = request()->get('startTime');
        $extraParams = request()->get('extraServices');
        $bookingType = request()->get('bookingType', 'date_time');
        $package_name = request()->get('tour_package');

        if ($bookingType == 'just_date' || $bookingType == 'package') {
            $startTime = $startDate;
        }


        $numberNight = 1;
        $numberGuest = $number_adult + $number_child + $number_infant;
        if (hh_compare_encrypt($experienceID, $experienceEncrypt) && $startDate && $endDate && $startTime) {
            $experienceObject = $this->getById($experienceID);
            $data = [
                'experienceID' => $experienceID,
                'numberAdult' => $number_adult,
                'numberChild' => $number_child,
                'numberInfant' => $number_infant,
                'max_people' => $experienceObject->number_of_guest,
                'startDate' => $startDate,
                'startTime' => $startTime,
                'endTime' => $startTime,
                'endDate' => $endDate,
                'bookingType' => $experienceObject->booking_type,
                'package_name' => $package_name
            ];

            $checkAvailability = $this->experienceValidation($data);

            if ($checkAvailability['status'] == 0) {
	            if($api){
		            return [
			            'status' => 0,
			            'message' => $checkAvailability['message']
		            ];
	            }else {
		            $this->sendJson( [
			            'status'  => 0,
			            'message' => view( 'common.alert', [ 'type'    => 'danger',
			                                                 'message' => $checkAvailability['message']
			            ] )->render()
		            ], true );
	            }
            } else {
                $basePrice = 0;
                if ($experienceObject->booking_type == 'date_time') {
                    $prices = $this->getRealPrice($startTime, $number_adult, $number_child, $number_infant, $experienceObject);
                    $basePrice = $prices['total'];
                    $data['groupPrices'] = $prices;
                } elseif ($experienceObject->booking_type == 'just_date') {
                    $prices = $this->getRealPrice($startDate, $number_adult, $number_child, $number_infant, $experienceObject);
                    $basePrice = $prices['total'];
                    $data['groupPrices'] = $prices;
                } elseif ($experienceObject->booking_type == 'package') {
                    $package = $this->getPackageByName($experienceObject, $package_name);
                    $base_price = (float)$package['price'];
                    $sale_price = $package['sale_price'];
                    if (!empty($sale_price) && (float)$sale_price < $base_price) {
                        $base_price = $sale_price;
                    }
                    $basePrice = $base_price;
                    $data['package'] = $package;
                    $data['package_price'] = $base_price;
                }
                $requiredExtra = $this->getRequiredExtraPrice($experienceObject, $numberNight);
                $extra = $this->getExtraPrice($experienceObject, $extraParams, $numberNight);

                $rules = [
                    [
                        'unit' => '+',
                        'price' => $basePrice
                    ],
                    [
                        'unit' => '+',
                        'price' => $requiredExtra
                    ],
                    [
                        'unit' => '+',
                        'price' => $extra
                    ],
                ];
                $taxRule = [];

                $taxData = \Cart::get_inst()->getTax('experience');

                if ($taxData['included'] == 'off') {
                    $taxRule = [
                        [
                            'unit' => 'tax',
                            'price' => $taxData['tax']
                        ]
                    ];
                }

                $data['numberNight'] = $numberNight;
                $data['numberGuest'] = $numberGuest;
                $totalData = \Cart::get_inst()->totalCalculation($rules, $taxRule);
                $cartData = [
                    'serviceID' => $experienceID,
                    'serviceObject' => serialize($experienceObject),
                    'serviceType' => 'experience',
                    'basePrice' => $basePrice,
                    'extraPrice' => $requiredExtra + $extra,
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

    public function experienceValidation($data)
    {
        $default = [
            'experienceID' => '',
            'numberAdult' => 0,
            'numberChild' => 0,
            'numberInfant' => 0,
            'max_people' => 1,
            'startDate' => '',
            'startTime' => '',
            'endDate' => '',
            'bookingType' => 'date_time',
            'package_name' => ''
        ];
        $data = wp_parse_args($data, $default);

        $experienceObject = $this->getById($data['experienceID']);
        if (is_null($experienceObject)) {
            return [
                'status' => 0,
                'message' => __('This experience is not available')
            ];
        }

        $experience_price = new ExperiencePrice();
        $avai_item = $experience_price->getPriceItem($data['experienceID'], $data['startTime'], $experienceObject->booking_type);

        if (is_null($avai_item)) {
            return [
                'status' => 0,
                'message' => __('This experience is not available in this time')
            ];
        }
        $avai_model = new ExperienceAvailability();
        $avai_items = $avai_model->getAvailabilityItems($data['experienceID'], $data['startDate'], $data['startDate']);
        if ($avai_items['total'] > 0) {
            return [
                'status' => 0,
                'message' => __('This experience is not available in this time')
            ];
        }
        if ($data['bookingType'] != 'package') {
            if ($data['numberAdult'] + $data['numberChild'] + $data['numberInfant'] > $data['max_people']) {
                return [
                    'status' => 0,
                    'message' => sprintf(__('The maximum number of people is %s'), $data['max_people'])
                ];
            }
            $experience_booking = new ExperienceBooking();
            $total_guest = $data['numberAdult'] + $data['numberChild'] + $data['numberInfant'];

            $total_people = $experience_booking->countPeopleExperienceBookingInTime($data['startTime'], $experienceObject->booking_type);
            $max_people = (int)$avai_item->max_people;
            if ($total_people + $total_guest > $max_people) {
                return [
                    'status' => 0,
                    'message' => _n("[0::This tour is available for %s guests][1::This tour is available for %s guest][2::This tour is available for %s guests]", $max_people - ($total_people + $total_guest))
                ];
            }
        } else {
            $package = $this->getPackageByName($experienceObject, $data['package_name']);
            if (empty($package)) {
                return [
                    'status' => 0,
                    'message' => __('This package is not available')
                ];
            }
        }

        return [
            'status' => 1,
            'message' => __('This date range is available')
        ];
    }

    public function _getTotalPriceExperience(Request $request)
    {
        $experienceID = request()->get('experienceID');
        $experienceEncrypt = request()->get('experienceEncrypt');

        $bookingType = request()->get('bookingType', 'date_time');
        $startTime = request()->get('startTime');
        $extraServices = request()->get('extraServices');
        $adult_number = (int)request()->get('num_adults', 0);
        $child_number = (int)request()->get('num_children', 0);
        $infant_number = (int)request()->get('num_infants', 0);
        $package_name = request()->get('tour_package');
        if ($bookingType == 'just_date' || $bookingType == 'package') {
            $startTime = strtotime(request()->get('checkIn'));
        }
        $total = 0;
        $prices = [];
        $html = '';
        if (hh_compare_encrypt($experienceID, $experienceEncrypt) && $startTime) {
            $experience = $this->getById($experienceID);
            if ($bookingType == 'package') {
                $package = $this->getPackageByName($experience, $package_name);
                if (!empty($package)) {
                    $base_price = (float)$package['price'];
                    $sale_price = $package['sale_price'];
                    if (!empty($sale_price) && (float)$sale_price < $base_price) {
                        $base_price = $sale_price;
                    }
                    $requiredExtra = $this->getRequiredExtraPrice($experience, 1);
                    $extra = $this->getExtraPrice($experience, $extraServices, 1);
                    $total = $base_price + $requiredExtra + $extra;

                    $html .= '<ul class="calculate-price">';
                    $html .= '<li class="item"><span class="title">' . __('Total') . '</span><span class="desc">' . convert_price($total) . '</span></li>';
                    $html .= '</div>';
                }
            } else {
                $price_categories = $experience->price_categories;
                $prices = $this->getRealPrice($startTime, $adult_number, $child_number, $infant_number, $experience);
                $requiredExtra = $this->getRequiredExtraPrice($experience, 1);
                $extra = $this->getExtraPrice($experience, $extraServices, 1);
                $total = $prices['total'] + $requiredExtra + $extra;
                $html .= '<ul class="calculate-price">';
                if (in_array('enable_adults', $price_categories)) {
                    $html .= '<li class="item"><span class="title">' . __('Adults') . '</span><span class="desc">' . convert_price($prices['adult']) . '</span></li>';
                }
                if (in_array('enable_children', $price_categories)) {
                    $html .= '<li class="item"><span class="title">' . __('Children') . '</span><span class="desc">' . convert_price($prices['child']) . '</span></li>';
                }
                if (in_array('enable_infants', $price_categories)) {
                    $html .= '<li class="item"><span class="title">' . __('Infants') . '</span><span class="desc">' . convert_price($prices['infant']) . '</span></li>';
                }
                $html .= '<li class="item"><span class="title">' . __('Total') . '</span><span class="desc">' . convert_price($total) . '</span></li>';
                $html .= '</div>';
            }
        }

        $this->sendJson([
            'status' => 1,
            'total' => $total,
            'prices' => $prices,
            'html' => $html
        ], true);
    }

    public function getRequiredExtraPrice($post, $night = 1)
    {
        $extras = $this->getExtraServices($post, 'required');
        $total = 0;
        if ($extras) {
            foreach ($extras as $extra) {
                $total += (float)$extra['price'];
            }
        }
        $total *= $night;

        return $total;
    }

    public function getExtraPrice($post, $extraParams = [], $night = 1)
    {
        $extras = $this->getExtraServices($post, 'not_required');
        $total = 0;
        if (!empty($extraParams)) {
            foreach ($extraParams as $extra) {
                foreach ($extras as $_extra) {
                    if ($extra === $_extra['name_unique']) {
                        $total += (float)$_extra['price'];
                    }
                }
            }
            $total *= $night;
        }

        return $total;
    }

    public function getRealPrice($startTime, $num_adult = 0, $num_child = 0, $num_infant = 0, $post = null)
    {
        $price_model = new ExperiencePrice();
        $total = 0;
        $adult = $child = $infant = 0;
        $customPrice = $price_model->getPriceItems($post->post_id, $startTime, $startTime, $post->booking_type);
        if ($customPrice['total'] > 0) {
            foreach ($customPrice['results'] as $item) {
                $adult += (float)$item->adult_price * $num_adult;
                $child += (float)$item->child_price * $num_child;
                $infant += (float)$item->infant_price * $num_infant;
                $total += $adult + $child + $infant;
            }
        }

        return [
            'total' => $total,
            'adult' => $adult,
            'child' => $child,
            'infant' => $infant
        ];
    }

    public function _getExperienceDateTime(Request $request)
    {
        $experienceID = request()->get('experience_id');
        $experienceEncrypt = request()->get('experience_encrypt');
        $start_time = strtotime(request()->get('start'));
        if ($start_time && hh_compare_encrypt($experienceID, $experienceEncrypt)) {
            $priceObject = new ExperiencePrice();
            $priceItems = $priceObject->getPriceItems($experienceID, $start_time, $start_time, 'just_date');
            $booking_model = new Booking();
            $booking_items = $booking_model->getBookingItems($experienceID, $start_time, $start_time, false, 'experience');
            if ($priceItems['total'] > 0) {
                $html = '<div class="form-group"><label for="startTime">' . __('Time') . '</label><select id="startTime" name="startTime" data-plugin="customselect"><option value="">' . __('---- Select ----') . '</option>';
                foreach ($priceItems['results'] as $item) {
                    $max_people = (int)$item->max_people;
                    $total_guest = 0;
                    if ($booking_items['total']) {
                        foreach ($booking_items['results'] as $booking_item) {
                            if ($booking_item->start_time == $item->start_time) {
                                $total_guest += (int)$booking_item->number_of_guest;
                            }
                        }
                    }
                    if ($max_people > $total_guest) {
                        $html .= '<option value="' . e($item->start_time) . '" data-max="' . (int)$item->max_people . '">' . e(date(hh_time_format(), $item->start_time)) . '</option>';
                    }
                }
                $html .= '</select></div>';

                $this->sendJson([
                    'status' => 1,
                    'html' => $html
                ], true);
            } else {
                $this->sendJson([
                    'status' => 0,
                    'message' => __('This date is unavailable')
                ], true);
            }
        } else {
            $this->sendJson([
                'status' => 0,
                'message' => __('The data is invalid')
            ], true);
        }
    }

    public function _getExperienceGuest(Request $request)
    {
        $experienceID = request()->get('experience_id');
        $experienceEncrypt = request()->get('experience_encrypt');
        $start_time = strtotime(request()->get('start'));
        if ($start_time && hh_compare_encrypt($experienceID, $experienceEncrypt)) {
            $priceObject = new ExperiencePrice();
            $priceItem = $priceObject->getPriceItem($experienceID, $start_time, 'just_date');
            if (!empty($priceItem)) {
                $this->sendJson([
                    'status' => 1,
                    'max_people' => (int)$priceItem->max_people
                ], true);
            } else {
                $this->sendJson([
                    'status' => 0,
                    'message' => __('This date is unavailable')
                ], true);
            }
        } else {
            $this->sendJson([
                'status' => 0,
                'message' => __('The data is invalid')
            ], true);
        }
    }

    public function _getExperienceAvailabilitySingle(Request $request)
    {
        $events['events'] = [];
        $startTime = strtotime(request()->get('startTime'));
        $endTime = strtotime(request()->get('endTime'));
        $experienceID = request()->get('experienceID');
        $experienceEncrypt = request()->get('experienceEncrypt');
        if ($startTime && $endTime && hh_compare_encrypt($experienceID, $experienceEncrypt)) {
            $price_model = new ExperiencePrice();
            $priceItems = $price_model->getPriceItems($experienceID, $startTime, $endTime, 'just_date');
            $avai_model = new ExperienceAvailability();
            $avai_items = $avai_model->getAvailabilityItems($experienceID, $startTime, $endTime);
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
        $this->sendJson($events, true);
    }

    public function _addNewExperience(Request $request)
    {
        $folder = $this->getFolder();
        $experience = new Experience();
        $newExperience = $experience->createExperience();
        return view("dashboard.screens.{$folder}.services.experience.add-new-experience", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'newExperience' => $newExperience]);
    }

    public function getById($experience_id, $global = false)
    {
        $experience_object = new Experience();
        $post_item = $experience_object->getById($experience_id);
        if (!is_null($post_item)) {
            $post_item = $this->setup_post_data($post_item);
        }
        if ($global) {
            global $post;
            $post = $post_item;
        }


        return $post_item;
    }

    public function getByName($experience_name, $global = false)
    {
        $experience_object = new Experience();
        $post_item = $experience_object->getByName($experience_name);
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

        $post->review_count = get_comment_number($post->post_id, 'experience');

        $tax = get_taxonomies('experience');
        foreach ($tax as $key => $tax_name) {
            $name = 'tax_' . Str::slug($tax_name, '_');
            $post->$name = $term_relation_object->get_the_terms($post->post_id,'experience', $key);
        }
        $post->itinerary = maybe_unserialize($post->itinerary);
        $post->extra = $this->getExtraServices($post);
        $post->required_extra = $this->getExtraServices($post, 'required');
        $post->not_required_extra = $this->getExtraServices($post, 'not_required');

        $post->unit = get_experience_unit($post);
        $post->price_categories = explode(',', $post->price_categories);

        $post->tour_packages = !empty($post->tour_packages) ? maybe_unserialize($post->tour_packages) : [];

        return $post;
    }


    public function getTourPackages($post)
    {
        return !empty($post->tour_packages) ? maybe_unserialize($post->tour_packages) : [];
    }

    public function getPackageByName($experience_id, $name)
    {
        if (is_object($experience_id)) {
            $packages = $this->getTourPackages($experience_id);
            foreach ($packages as $key => $package) {
                if ($package['name'] === $name) {
                    return $package;
                }
            }
            return [];
        } else {
            $post = get_post($experience_id, 'experience');
            if (is_object($post)) {
                $packages = $this->getTourPackages($post);
                foreach ($packages as $key => $package) {
                    if ($package['name'] === $name) {
                        return $package;
                    }
                }
                return [];
            } else {
                return [];
            }
        }
    }

    public function getExtraServices($post, $select = 'all')
    {
        $return = [];
        $extra = maybe_unserialize($post->extra_services);
        if (!empty($extra) && is_array($extra)) {
            foreach ($extra as $key => $value) {
                if ($select == 'required' && (isset($value['required']) && $value['required'])) {
                    $return[] = $value;
                }
                if ($select == 'not_required' && (!isset($value['required']) || !$value['required'])) {
                    $return[] = $value;
                }
            }
            return $return;
        }
        return false;
    }

    public function _updateExperience(Request $request)
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
            $experience = new Experience();
            $experienceObject = $experience->getById($postID);
            if (!is_object($experienceObject)) {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('This experience is not available')
                ], true);
            }

            $data = [];

            if ($experienceObject->status == 'revision') {
                $data['status'] = 'pending';
            }
            foreach ($fields as $field) {
                $field = \ThemeOptions::mergeField($field);
                $value = \ThemeOptions::fetchField($field);
                if (!$field['excluded'] && !empty($value)) {
                    if ($field['field_type'] == 'meta') {
                        $data[$field['id']] = $value;
                        if ($field['type'] == 'price_categories') {
                            $data['price_primary'] = request()->get('price_primary', 'adult_price');
                            $data['adult_price'] = (float)request()->get('adult_price');
                            $data['child_price'] = (float)request()->get('child_price');
                            $data['infant_price'] = (float)request()->get('infant_price');
                            $data['base_price'] = $data[$data['price_primary']];
                        }
                        $booking_type = $request->get('booking_type');
                        if ($booking_type == 'package') {
                            $data['base_price'] = $request->get('base_price');
                        }
                    } elseif ($field['field_type'] == 'taxonomy') {
                        $value = (array)$value;
                        $taxonomy = explode(':', $field['choices'])[1];
                        $termRelation = new TermRelation();
                        $termRelation->deleteRelationByServiceID($postID, $taxonomy);
                        foreach ($value as $termID) {
                            $termRelation->createRelation($termID, $postID, 'experience');
                        }
                        $data[$field['id']] = implode(',', $value);
                    } elseif ($field['field_type'] == 'location') {
                        if (is_array($value)) {
                            foreach ($value as $key => $_val) {
                                $data[$field['id'] . '_' . $key] = $_val;
                            }
                        }
                    }
                }
            }
            if (!empty($data)) {
                if (isset($_POST['post_slug']) && (!isset($data['post_slug']) || empty($data['post_slug']))) {
                    $data['post_slug'] = Str::slug(esc_html(request()->get($post_title_field, 'new-experience-' . time())));
                }
                $experience = new Experience();
                $experience->updateExperience($data, $postID);
            }
            do_action('hh_saved_experience_meta', $postID);
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


    public function _changeStatusExperience(Request $request)
    {
        $experience_id = request()->get('serviceID');
        $experience_encrypt = request()->get('serviceEncrypt');
        $status = request()->get('status', '');

        if (!hh_compare_encrypt($experience_id, $experience_encrypt) || !$status) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('The data is invalid')
            ], true);
        }

        $experience_model = new Experience();
        $updated = $experience_model->updateStatus($experience_id, $status);
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

    public function _deleteExperienceItem(Request $request)
    {
        $experience_id = request()->get('serviceID');
        $experience_encrypt = request()->get('serviceEncrypt');

        if (!hh_compare_encrypt($experience_id, $experience_encrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('The experience is not exist')
            ], true);
        }

        $experience_model = new Experience();

        $delete = $experience_model->deleteExperienceItem($experience_id);
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
            'message' => __('Has error when delete this experience')
        ], true);
    }

    public function _editExperience(Request $request, $id = null)
    {
        $folder = $this->getFolder();
        $experience = new Experience();
        $experienceObject = $experience->getById($id);
        if (is_object($experienceObject) && user_can_edit_service($experienceObject)) {
            return view("dashboard.screens.{$folder}.services.experience.edit-experience", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'newExperience' => $id]);
        } else {
            return redirect()->to(dashboard_url('my-experience'));
        }
    }

    public function _myExperience(Request $request, $page = 1)
    {
        $folder = $this->getFolder();

        $search = request()->get('_s');
        $orderBy = request()->get('orderby', 'post_id');
        $order = request()->get('order', 'desc');
        $booking_type = request()->get('booking_type');
        $status = request()->get('status');

        $allExperiences = $this->getAllExperiences([
            'search' => $search,
            'orderby' => $orderBy,
            'order' => $order,
            'booking_type' => $booking_type,
            'status' => $status,
            'page' => $page
        ]);

        return view("dashboard.screens.{$folder}.services.experience.my-experience", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'allExperiences' => $allExperiences]);
    }

    public function _duplicateExperience(Request $request)
    {
        $experience_id = request()->get('serviceID');
        $experience_encrypt = request()->get('serviceEncrypt');

        if (!hh_compare_encrypt($experience_id, $experience_encrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('The experience is not exist')
            ], true);
        }

        $experience = new Experience();
        $new_experience = $experience->duplicate($experience_id);
        if ($new_experience) {
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Duplicated new experience successful'),
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

    public function _getExperienceSingle(Request $request, $experience_id, $experience_name = null)
    {
        $experienceObject = $this->getById($experience_id, true);

        if (is_null($experienceObject) || !$experienceObject || $experienceObject->status != 'publish') {
            return view('frontend.404');
        } else {
            return view('frontend.experience.default');
        }
    }

    public function getAllExperiences($data = [])
    {
        $experience = new Experience();
        return $experience->getAllExperiences($data);
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

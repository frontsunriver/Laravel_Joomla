<?php

namespace App\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Comment;
use App\Models\Home;
use App\Models\HomeAvailability;
use App\Models\HomePrice;
use App\Models\Taxonomy;
use App\Models\TermRelation;
use ICal\ICal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;
use Sentinel;

class HomeController extends Controller
{
    public function __construct()
    {
        add_action('hh_dashboard_breadcrumb', [$this, '_addCreateHomeButton']);
    }

    public function assignDataByUserID($user_id, $user_assign)
    {
        $model = new Home();
        $model->updateAuthor(['author' => $user_assign], $user_id);
    }

    public function deleteDataByUserID($user_id)
    {
        $model = new Home();
        $allHomes = $model->getAllHomes([
            'author' => $user_id,
            'number' => -1
        ]);
        if ($allHomes['total'] > 0) {
            foreach ($allHomes['results'] as $item) {
                $model->deleteHomeItem($item->post_id);
            }
        }
    }

    public function _bulkActions(Request $request)
    {
        $action = request()->get('action', '');
        $post_id = request()->get('post_id', '');

        if (!empty($action) && !empty($post_id)) {
            $post_id = explode(',', $post_id);
            $homeModel = new Home();
            switch ($action) {
                case 'delete':
                    $homeModel->whereIn('post_id', $post_id)->delete();
                    $commentModel = new Comment();
                    $commentModel->whereIn('post_id', $post_id)->where('post_type', 'home')->delete();
                    $termRelationModel = new TermRelation();
                    $termRelationModel->whereIn('service_id', $post_id)->delete();
                    $homePriceModel = new HomePrice();
                    $homePriceModel->whereIn('home_id', $post_id)->delete();
                    $homeAvailabilityModel = new HomeAvailability();
                    $homeAvailabilityModel->whereIn('home_id', $post_id)->delete();
                    break;
                case 'publish':
                case 'pending':
                case 'draft':
                case 'trash':
                    $homeModel->updateMultiHome([
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
        $home_model = new Home();
        $posts = $home_model->getAllIcalItems();
        if (is_object($posts)) {
            foreach ($posts as $post) {
                $home_id = $post->post_id;
                $icalList = maybe_unserialize($post->import_ical_url);
                if (is_array($icalList)) {
                    foreach ($icalList as $item) {
                        $url = $item['ical_url'];
                        try {
                            $ical = new ICal($url, [
                                'defaultTimeZone' => get_timezone(),
                            ]);
                            if ($ical->hasEvents()) {
                                $home_avai = new HomeAvailability();
                                $today = strtotime(date('Y-m-d'));
                                $events = $ical->events();
                                foreach ($events as $event) {
                                    if (!empty($event->dtstart_tz) && !empty($event->dtend_tz)) {
                                        $dtstart = $ical->iCalDateToDateTime($event->dtstart_tz)->format('U');
                                        $dtend = $ical->iCalDateToDateTime($event->dtend_tz)->format('U');
                                        $total_minutes = hh_date_diff($dtstart, $dtend, 'minute');
                                    } else {
                                        $dtstart = $ical->iCalDateToDateTime($event->dtstart)->format('U');
                                        $dtend = $ical->iCalDateToDateTime($event->dtend)->format('U');
                                        $total_minutes = hh_date_diff($dtstart, $dtend, 'minute');
                                    }
                                    if ($dtstart >= $today) {
                                        if ($total_minutes >= 1440) {
                                            $dtend = strtotime('-1 day', $dtend);
                                        }

                                        $hasAvai = $home_avai->getItem($home_id, $dtstart, $dtend, 'time');
                                        if (!$hasAvai) {
                                            $data = [
                                                'home_id' => $home_id,
                                                'total_minutes' => $total_minutes,
                                                'booking_id' => '',
                                                'type' => 'ical',
                                                'summary' => !empty($event->summary) ? $event->summary : __('Unavailable'),
                                                'start_time' => $dtstart,
                                                'start_date' => $dtstart,
                                                'end_time' => $dtend,
                                                'end_date' => $dtend,
                                            ];
                                            if ($total_minutes != 1440) {
                                                $data['start_date'] = strtotime(date('Y-m-d', $dtstart));
                                                $data['end_date'] = strtotime(date('Y-m-d', $dtend));
                                            }
                                            $home_avai->createAvailability($data);
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

    public function _getAvailabilityHome(Request $request)
    {
        $events['events'] = [];
        $startTime = strtotime(request()->get('start'));
        $endTime = strtotime(request()->get('end'));
        $homeID = request()->get('post_id');
        $homeEncrypt = request()->get('post_encrypt');

        if ($startTime && $endTime && hh_compare_encrypt($homeID, $homeEncrypt)) {
            $price_model = new HomePrice();
            $avai_model = new HomeAvailability();
            $priceItems = $price_model->getPriceItems($homeID, $startTime, $endTime, 'on');
            $homeObject = $this->getById($homeID);

            $price = (float)$homeObject->base_price;
            $wprice = $homeObject->weekend_price;
            $ruleWeekend = $homeObject->weekend_to_apply;
            if ($homeObject->booking_type == 'per_night') {
                $endTime = strtotime('-1 day', $endTime);
                $avaiItems = $avai_model->getAvailabilityItems($homeID, $startTime, $endTime);
                for ($i = $startTime; $i <= $endTime; $i = strtotime('+1 day', $i)) {
                    $status = 'available';
                    $event = convert_price($price);
                    $inCustom = false;
                    foreach ($avaiItems['results'] as $avaiItem) {
                        if ($i >= $avaiItem->start_date && $i <= $avaiItem->end_date) {
                            if ($avaiItem->booking_id == 0 && $avaiItem->total_minutes == 1440) {
                                $status = 'not_available';
                                $event = __('Unavailable');
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
                        'event' => ''
                    ];
                }
            } elseif ($homeObject->booking_type == 'per_hour') {
                $avaiItems = $avai_model->getAvailabilityTimeItems($homeID, $startTime, $endTime);
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
                        'event' => ''
                    ];
                }
            }

        }

        $this->sendJson($events, true);
    }

    public function _getIcalUrl(Request $request, $home_id)
    {
        $home_avai = new HomeAvailability();
        $home_object = get_post($home_id, 'home');
        $today = strtotime(date('Y-m-d'));
        $allItems = $home_avai->getAvailabilityItems($home_id, $today);
        date_default_timezone_set(get_timezone());
        $vCalendar = new \Eluceo\iCal\Component\Calendar(url('/'));
        if ($home_object->booking_type == 'per_hour') {
            if ($allItems['total']) {
                foreach ($allItems['results'] as $item) {
                    if (empty($item->type)) {
                        $vEvent = new \Eluceo\iCal\Component\Event();
                        if ($item->booking_id == 0 && $item->total_minutes == 1440) {

                            $vEvent->setDtStart(new \DateTime(date('Y-m-d', $item->start_date)))
                                ->setDtEnd(new \DateTime(date('Y-m-d', $item->end_date)))
                                ->setNoTime(true);
                            $vEvent->setSummary(__('Unavailable'));
                        } else {
                            $vEvent->setDtStart(new \DateTime(date('Y-m-d H:i:s', strtotime('+30 minutes', $item->start_time))))
                                ->setDtEnd(new \DateTime(date('Y-m-d H:i:s', strtotime('+30 minutes', $item->end_time))))
                                ->setNoTime(false);
                            $vEvent->setSummary(sprintf(__('Booking ID: %s'), $item->booking_id));
                        }

                        $vCalendar->addComponent($vEvent);
                    }
                }
            }
        } elseif ($home_object->booking_type == 'per_night') {
            foreach ($allItems['results'] as $item) {

                $vEvent = new \Eluceo\iCal\Component\Event();
                if ($item->booking_id == 0 && $item->total_minutes == 1440) {

                    $vEvent->setDtStart(new \DateTime(date('Y-m-d', $item->start_date)))
                        ->setDtEnd(new \DateTime(date('Y-m-d', $item->end_date)))
                        ->setNoTime(true);
                    $vEvent->setSummary(__('Unavailable'));
                } else {
                    $vEvent->setDtStart(new \DateTime(date('Y-m-d', strtotime('+1 day', $item->start_time))))
                        ->setDtEnd(new \DateTime(date('Y-m-d', strtotime('+1 day', $item->end_time))))
                        ->setNoTime(false);
                    $vEvent->setSummary(sprintf(__('Booking ID: %s'), $item->booking_id));
                }

                $vCalendar->addComponent($vEvent);
            }
        }
        @ob_clean();
        $file_name = 'ical-' . post_type_info('home')['slug'] . '-' . $home_id;
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . trim($file_name) . '.ics"');
        echo esc_text($vCalendar->render());
        exit;
    }

    public function _updateHomeAvailability($booking_id, $status)
    {
        reset_booking_data();
        $booking = get_booking($booking_id);

        $avai_model = new HomeAvailability();
        if (in_array($booking->status, ['canceled', 'pending', 'refunded'])) {
            $avai_model->deleteAvailabilityByBooking($booking_id);
        } else {
            $has_avai_booking = $avai_model->getItemByBooking($booking_id);
            if (!$has_avai_booking) {
                $serviceObject = get_booking_data($booking_id, 'serviceObject', 'home');
                if ($serviceObject->booking_type == 'per_night') {
                    $start = $booking->start_date;
                    $end = strtotime('-1 day', $booking->end_date);
                    $avai_model->createAvailability([
                        'home_id' => $booking->service_id,
                        'start_time' => $booking->start_time,
                        'start_date' => $start,
                        'end_time' => $booking->end_time,
                        'end_date' => $end,
                        'booking_id' => $booking_id,
                        'total_minutes' => $booking->total_minutes
                    ]);
                } elseif ($serviceObject->booking_type == 'per_hour') {
                    $start = $booking->start_time;
                    $end = strtotime('-30 minutes', $booking->end_time);
                    $avai_model->createAvailability([
                        'home_id' => $booking->service_id,
                        'start_time' => $start,
                        'start_date' => $booking->start_date,
                        'end_time' => $end,
                        'end_date' => $booking->end_date,
                        'booking_id' => $booking_id,
                        'total_minutes' => $booking->total_minutes
                    ]);
                }
            }

        }

    }

    public function advancedSearch($page = '1') {
        $page = 5;
        return view("frontend.home.search.search", ['page'=>$page]);
    }

    public function _getSearchResult()
    {
        $home = new Home();
        $post_data = request()->all();
        $data = $home->getSearchResult($post_data);
        $search_string = view('frontend.home.search.search_string', [
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
            foreach ($data['results'] as $k => $item) {
                $html .= view('frontend.home.loop.list', ['item' => $item])->render();
                $locations[] = [
                    'lat' => $item->location_lat,
                    'lng' => $item->location_lng,
                    'price' => convert_price($item->base_price),
                    'post_id' => $item->post_id,
                    'title' => get_translate($item->post_title),
                    'url' => get_home_permalink($item->post_id, $item->post_slug),
                    'thumbnail' => get_attachment_url($item->thumbnail_id, [75, 75])
                ];
            }
        } else {
            $lat = request()->get('lat', 0);
            $lng = request()->get('lng', 0);
            $locations[0] = [
                'lat' => $lat,
                'lng' => $lng
            ];
        }

        $pag = view('frontend.home.search.search_pag', [
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
        return view("frontend.home.search.search", ['page' => $page]);
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
        $post = get_post($post_id, 'home');
        $admin = get_admin_user();
        $partner = get_user_by_id($post->author);

        send_mail(esc_html($email), esc_html($name), $admin->email, sprintf(__('[%s] Have a booking request from [Home ID: %s] - %s'), get_option('site_name'), $post_id, $post->post_title), balanceTags($message));
        send_mail(esc_html($email), esc_html($name), $partner->email, sprintf(__('[%s] Have a booking request from [Home ID: %s] - %s'), get_option('site_name'), $post_id, $post->post_title), balanceTags($message));
        return $this->sendJson([
            'status' => 1,
            'message' => view('common.alert', ['type' => 'success', 'message' => __('Sent! Please wait for a response from the partner')])->render()
        ]);
    }

    public function _getHomeNearYouAjax(Request $request)
    {
        $lat = request()->get('lat');
        $lng = request()->get('lng');
        $radius = request()->get('radius', 50);
        $html = '';
        if ($lat && $lng) {
            $list_services = $this->listOfHomes([
                'number' => 8,
                'location' => [
                    'lat' => $lat,
                    'lng' => $lng,
                    'radius' => $radius
                ]
            ]);

            if (count($list_services['results'])) {
                start_get_view();
                ?>
                <div class="hh-list-of-services">
                    <div class="row">
                        <?php foreach ($list_services['results'] as $item) { ?>
                            <div class="col-6 col-md-4 col-lg-3">
                                <?php echo view('frontend.home.loop.grid', ['item' => $item])->render() ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php
                $html = end_get_view();
            } else {
                $html = '<h4 class="mt-3 text-center">' . __('Not found') . '</h4>';
            }
        }

        return $this->sendJson([
            'html' => $html
        ]);
    }

    public function _getLatestHomeAjax(Request $request)
    {
        $html = '';
        $list_services = $this->listOfHomes([
            'number' => 8,
        ]);

        if (count($list_services['results'])) {
            start_get_view();
            ?>
            <div class="hh-list-of-services">
                <div class="row">
                    <?php foreach ($list_services['results'] as $item) { ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <?php echo view('frontend.home.loop.grid', ['item' => $item])->render() ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php
            $html = end_get_view();
        } else {
            $html = '<h4 class="mt-3 text-center">' . __('Not found') . '</h4>';
        }


        return $this->sendJson([
            'html' => $html
        ]);
    }

    public function _addCreateHomeButton()
    {
        $screen = current_screen();
        if ($screen == 'my-home') {
            echo view("dashboard.components.services.home.quick-add-home")->render();
        }
    }

    public function listOfHomes($data = [])
    {
        $home = new Home();
        return $home->listOfHomes($data);
    }

    public function getAllHomes($data = [])
    {
        $home = new Home();
        return $home->getAllHomes($data);
    }

    public function getById($home_id, $global = false)
    {
        $home_object = new Home();
        $post_item = $home_object->getById($home_id);
        if (!is_null($post_item)) {
            $post_item = $this->setup_post_data($post_item);
        }
        if ($global) {
            global $post;
            $home_price = new HomePrice();
            $post_item->period_stay_date = $home_price->getAllPrices($home_id);
            $post = $post_item;
        }

        return $post_item;
    }

    public function getByName($home_name, $global = false)
    {
        $home_object = new Home();
        $post_item = $home_object->getByName($home_name);
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

        $post->review_count = get_comment_number($post->post_id, 'home');

        $tax_model = new Taxonomy();
        $taxonomies = $tax_model->getAll('home');
        foreach ($taxonomies as $key => $taxonomy) {
            $name = 'tax_' . str_replace('-', '_', $taxonomy->taxonomy_name);
            $post->$name = $term_relation_object->get_the_terms($post->post_id, 'home', $taxonomy->taxonomy_id);
        }

        $post->extra = $this->getExtraServices($post);
        $post->required_extra = $this->getExtraServices($post, 'required');
        $post->not_required_extra = $this->getExtraServices($post, 'not_required');

        $post->unit = get_home_unit($post);

        return $post;
    }

    public function getMinMaxPrice()
    {
        $home_price_model = new HomePrice();
        $home_model = new Home();
        $minMaxPrice = $home_model->getMinMaxPrice();
        $homePriceMinMax = $home_price_model->getMinMaxPrice();
        if(!isset($homePriceMinMax['min']) || empty($homePriceMinMax['min'])){
            if (!isset($minMaxPrice['min']) || empty($minMaxPrice['min'])) {
                $minMaxPrice['min'] = 0;
            }
            if (!isset($minMaxPrice['max'])) {
                $minMaxPrice['max'] = 500;
            }
        }
        

        $minMaxPrice['min'] = convert_price($homePriceMinMax['min'], false, false);
        $minMaxPrice['max'] = convert_price($homePriceMinMax['max'], false, false);

        return $minMaxPrice;
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

    public function getRealPrice($post, $startTime, $endTime, $num_guest = 0)
    {
        $price_model = new HomePrice();
        $price = $post->base_price;
        $weekendPrice = $post->weekend_price;
        $weekendApply = $post->weekend_to_apply;
        $customPrice = $price_model->getPriceItems($post->post_id, $startTime, $endTime);
        $total = 0;

        $use_long_price = $post->use_long_price;
        $price_7_day = (float)$post->price_7_day;
        $price_14_day = (float)$post->price_14_day;
        $price_30_day = (float)$post->price_30_day;
        $totalDay = hh_date_diff($startTime, $endTime);

        $specialPrice = array();

        foreach ($customPrice['results'] as $item) {
            if($item->first_minute == 'on' || $item->last_minute == 'on'){
                array_push($specialPrice, $item);
            }
        }

        for ($i = $startTime; $i < $endTime; $i = strtotime('+1 day', $i)) {
            $inCustom = false;
            $special_flag = false;
            foreach ($specialPrice as $record) {
                if ($i >= $record->start_time && $i <= $record->end_time) {
                    $special_flag = true;
                    $inCustom = true;
                    $total += (float)$post->base_price - ($post->base_price * ($record->discount_percent) / 100);
                    break;
                }
            }
            if(!$special_flag){
                foreach ($customPrice['results'] as $item) {
                    if ($i >= $item->start_time && $i <= $item->end_time) {
                        if($item->price == 0 && $item->price_per_night > 0){
                            $total += (float)$item->price_per_night;
                        }else {
                            $total += (float)$item->price;
                        }
                        $inCustom = true;
                        break;
                    }
                }
            }
            
            
            if (!$inCustom) {
                if ($use_long_price == 'on') {
                    if ($totalDay >= 30) {
                        if ($price_30_day == 0) {
                            $total += $price;
                        } else {
                            $total += $price_30_day;
                        }
                    } elseif ($totalDay >= 14) {
                        if ($price_14_day == 0) {
                            $total += $price;
                        } else {
                            $total += $price_14_day;
                        }
                    } elseif ($totalDay >= 7) {
                        if ($price_7_day == 0) {
                            $total += $price;
                        } else {
                            $total += $price_7_day;
                        }
                    } else {
                        if (is_weekend($i, $weekendApply)) {
                            $total += is_null($weekendPrice) ? $price : (float)$weekendPrice;
                        } else {
                            $total += $price;
                        }
                    }
                } else {
                    if (is_weekend($i, $weekendApply)) {
                        $total += is_null($weekendPrice) ? $price : (float)$weekendPrice;
                    } else {
                        $total += $price;
                    }
                }
            }
        }
        if ($post->enable_extra_guest == 'on') {
            $number_guest = (int)$post->number_of_guest;
            $apply_to_guest = (int)$post->apply_to_guest;
            $extra_guest_price = (float)$post->extra_guest_price;
            if ($apply_to_guest > $number_guest) {
                $apply_to_guest = $number_guest;
            }
            $num_guest_applied = $num_guest - $apply_to_guest;
            if ($num_guest_applied > 0) {
                $total += $num_guest_applied * $extra_guest_price;
            }
        }

        return $total;
    }

    public function getRealPriceByTime($post, $startTime, $endTime, $num_guest = 0)
    {
        $price_model = new HomePrice();

        $price = $post->base_price;
        $weekendPrice = $post->weekend_price;
        $weekendApply = $post->weekend_to_apply;

        $totalHour = ceil(hh_date_diff($startTime, $endTime, 'minute') / 60);

        $start_date = strtotime(date('Y-m-d', $startTime));

        $customPrice = $price_model->getPriceItems($post->post_id, $startTime, $endTime);
        $total = 0;
        $inCustom = false;
        foreach ($customPrice['results'] as $item) {
            if ($start_date >= $item->start_time && $start_date <= $item->end_time) {
                if($item->price == 0){
                    $total += (float)$item->price_per_night;
                }else {
                    $total += (float)$item->price;
                }
                $inCustom = true;
                break;
            }
        }
        if (!$inCustom) {
            if (is_weekend($start_date, $weekendApply)) {
                $total += is_null($weekendPrice) ? $price : (float)$weekendPrice;
            } else {
                $total += $price;
            }
        }
        $total *= $totalHour;

        if ($post->enable_extra_guest == 'on') {
            $number_guest = (int)$post->number_of_guest;
            $apply_to_guest = (int)$post->apply_to_guest;
            $extra_guest_price = (float)$post->extra_guest_price;
            if ($apply_to_guest > $number_guest) {
                $apply_to_guest = $number_guest;
            }
            $num_guest_applied = $num_guest - $apply_to_guest;
            if ($num_guest_applied > 0) {
                $total += $num_guest_applied * $extra_guest_price;
            }
        }

        return $total;
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

    public function _addToCartHome(Request $request, $api = false)
    {
        $homeID = (int)request()->get('homeID');
        $homeEncrypt = request()->get('homeEncrypt');

        $number_adult = (int)request()->get('num_adults', 1);
        $number_child = (int)request()->get('num_children');
        $number_infant = (int)request()->get('num_infants');
        $startDate = strtotime(request()->get('checkIn'));
        $startTime = request()->get('startTime');
        $endDate = strtotime(request()->get('checkOut'));
        $endTime = request()->get('endTime');
        $extraParams = request()->get('extraServices');
        $numberNight = hh_date_diff($startDate, $endDate);
        $numberGuest = $number_adult + $number_child;
        if (hh_compare_encrypt($homeID, $homeEncrypt) && $startDate && $endDate) {
            $homeObject = $this->getById($homeID);
            $data = [
                'homeID' => $homeID,
                'numberAdult' => $number_adult,
                'numberChild' => $number_child,
                'numberInfant' => $number_infant,
                'minStay' => $homeObject->min_stay,
                'maxStay' => $homeObject->max_stay,
                'guest' => $homeObject->number_of_guest,
                'startDate' => $startDate,
                'startTime' => strtotime(date('Y-m-d', $startDate) . ' ' . $startTime),
                'endDate' => $endDate,
                'endTime' => strtotime(date('Y-m-d', $endDate) . ' ' . $endTime),
                'bookingType' => $homeObject->booking_type
            ];
            

            $checkAvailability = $this->homeValidation($data);
            if ($checkAvailability['status'] == 0) {
                if ($api) {
                    return [
                        'status' => 0,
                        'message' => $checkAvailability['message']
                    ];
                } else {
                    $this->sendJson([
                        'status' => 0,
                        'message' => view('common.alert', ['type' => 'danger',
                            'message' => $checkAvailability['message']
                        ])->render()
                    ], true);
                }
            } else {
                $bookingType = $homeObject->booking_type;

                if ($bookingType == 'per_night') {
                    // $homePriceModel = new HomePrice();
                    // $checkRentValidate = $homePriceModel->getPriceItems($homeID, $startDate, $endDate, 'on');
                    // if($checkRentValidate['total'] > 0){
                    //     $basePrice = $checkRentValidate['results'][0]->price_per_night;
                    //     $requiredExtra = $this->getRequiredExtraPrice($homeObject, $numberNight);
                    //     $extra = $this->getExtraPrice($homeObject, $extraParams, $numberNight);
                    // }else {
                        
                    // }
                    $basePrice = $this->getRealPrice($homeObject, $startDate, $endDate, $numberGuest);
                    $requiredExtra = $this->getRequiredExtraPrice($homeObject, $numberNight);
                    $extra = $this->getExtraPrice($homeObject, $extraParams, $numberNight);
                    
                } elseif ($bookingType == 'per_hour') {
                    $numberNight = ceil(hh_date_diff($data['startTime'], $data['endTime'], 'minute') / 60);
                    $basePrice = $this->getRealPriceByTime($homeObject, $data['startTime'], $data['endTime'], $numberGuest);
                    $requiredExtra = $this->getRequiredExtraPrice($homeObject, $numberNight);
                    $extra = $this->getExtraPrice($homeObject, $extraParams, $numberNight);
                }

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

                $taxData = \Cart::get_inst()->getTax('home');

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
                    'serviceID' => $homeID,
                    'serviceObject' => serialize($homeObject),
                    'serviceType' => 'home',
                    'basePrice' => $basePrice,
                    'extraPrice' => $requiredExtra + $extra,
                    'subTotal' => $totalData['subTotal'],
                    'tax' => $taxData,
                    'amount' => $totalData['amount'],
                    'cartData' => $data,
                    'discount_percent' => 0,
                ];

                $cartData = apply_filters('hh_cart_data_before_add_to_cart', $cartData);

                $specialData = $this->checkAndGetSpecial($homeObject, $startDate, $endDate);
                if($specialData['special_count'] > 0){
                    $cartData['basePrice'] = $specialData['total_budget'];
                    $cartData['discount_percent'] = $specialData['discount_percent'];
                }

                if ($api) {
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

        if ($api) {
            return [
                'status' => 0,
                'message' => __('The data is invalid')
            ];
        } else {
            $this->sendJson([
                'status' => 0,
                'message' => view('common.alert', ['type' => 'warning',
                    'message' => __('The data is invalid')
                ])->render()
            ], true);
        }
    }

    public function checkAndGetSpecial($post, $startTime, $endTime) {
        $price_model = new HomePrice();
        $price = $post->base_price;
        $booking_type = $post->booking_type;
        if($booking_type == 'per_night'){
            $customPrice = $price_model->getPriceItems($post->post_id, $startTime, $endTime);
            $total = 0;
    
            $specialPrices = array();
            $periodPrices = array();
            
            foreach ($customPrice['results'] as $key => $item) {
                if($item->first_minute == 'on' || $item->last_minute == 'on'){
                    array_push($specialPrices, $item);
                }else {
                    array_push($periodPrices, $item);
                }
            }
            $total_array = array();
            foreach ($periodPrices as $item) {
                $special_flag = false;
                foreach ($specialPrices as $value) {
                    if($value->start_time >= $item->start_time && $value->end_time <= $item->end_time){
                        $special_flag = true;
                        $item->discount_percent = $value->discount_percent;
                        $item->first_minute = 'on';
                        $item->last_minute = 'on';
                        array_push($total_array, $item);
                        break;
                    }
                }
                if(!$special_flag) {
                    array_push($total_array, $item);
                }
            }
            $special_count = 0;
            $discount_percent = 0;
            for ($i = $startTime; $i < $endTime; $i = strtotime('+1 day', $i)) {
                foreach ($total_array as $record) {
                    if ($i >= $record->start_time && $i <= $record->end_time) {
                        if($record->first_minute == 'on' || $record->last_minute == 'on'){
                            $special_count++;
                            $discount_percent = $record->discount_percent;
                            $total += (float)$post->base_price;
                        } else if($item->price == 0 && $item->price_per_night > 0){
                            $total += (float)$item->price_per_night;
                        }else {
                            $total += (float)$item->price;
                        }
                    }
                }
            }
            $result = array('special_count' => $special_count, 'total_budget' => $total, 'discount_percent' => $discount_percent);
        }else {
            $result = array('special_count' => 0, 'total_budget' => 0);
        }
        return $result;
    }

    public function homeValidation($data)
    {
        $default = [
            'homeID' => '',
            'numberAdult' => 0,
            'numberChild' => 0,
            'numberInfant' => 0,
            'minStay' => 1,
            'maxStay' => -1,
            'guest' => 1,
            'startDate' => '',
            'startTime' => '',
            'endDate' => '',
            'endTime' => '',
            'bookingType' => ''
        ];
        $data = wp_parse_args($data, $default);

        $homeObject = $this->getById($data['homeID']);
        if (is_null($homeObject)) {
            return [
                'status' => 0,
                'message' => __('This home is not available')
            ];
        }
        if ($data['numberAdult'] + $data['numberChild'] > $data['guest']) {
            return [
                'status' => 0,
                'message' => sprintf(__('The maximum number of people is %s'), $data['guest'])
            ];
        }

        if ($data['bookingType'] == 'per_hour') {
            if (hh_date_diff($data['startTime'], $data['endTime'], 'hour') < $data['minStay']) {
                return [
                    'status' => 0,
                    'message' => _n("[0::The min stay is %s hours][1::The min stay is %s hour][2::The min stay is %s hours]", $data['minStay'])
                ];
            }
            if (hh_date_diff($data['startTime'], $data['endTime'], 'hour') > $data['maxStay'] && $data['maxStay'] != -1) {
                return [
                    'status' => 0,
                    'message' => _n("[0::The max stay is %s hours][1::The max stay is %s hour][2::The max stay is %s hours]", $data['minStay'])
                ];
            }
        } elseif ($data['bookingType'] == 'per_night') {

            if (hh_date_diff($data['startDate'], $data['endDate']) < $data['minStay']) {
                return [
                    'status' => 0,
                    'message' => _n("[0::The min stay is %s day][1::The min stay is %s day][2::The min stay is %s day]", $data['minStay'])
                ];
            }
            if (hh_date_diff($data['startDate'], $data['endDate']) > $data['maxStay'] && $data['maxStay'] != -1) {
                return [
                    'status' => 0,
                    'message' => _n("[0::The max stay is %s day][1::The max stay is %s day][2::The max stay is %s day]", $data['minStay'])
                ];
            }
        }
        $avai_model = new HomeAvailability();

        if ($data['bookingType'] == 'per_night') {
            $start = $data['startDate'];
            $end = strtotime('-1 day', $data['endDate']);
            $avai = $avai_model->getAvailabilityItems($data['homeID'], $start, $end);

            if ($avai['total'] > 0) {
                $status = true;
                for ($i = $start; $i <= $end; $i = strtotime('+1 day', $i)) {
                    foreach ($avai['results'] as $item) {
                        if ($i >= $item->start_date && $i <= $item->end_date) {
                            $status = false;
                            break;
                        }
                    }
                    if (!$status) {
                        break;
                    }
                }
                if (!$status) {
                    return [
                        'status' => 0,
                        'message' => __('This date range is not available')
                    ];
                }
            }
        } elseif ($data['bookingType'] == 'per_hour') {
            if (is_timestamp($data['startTime']) && is_timestamp($data['endTime']) && $data['startTime'] < $data['endTime']) {
                $start = $data['startTime'];
                $end = strtotime('-30 minutes', $data['endTime']);
                $avai = $avai_model->getAvailabilityTimeItems($data['homeID'], $data['startDate'], $data['endDate'], false);
                if ($avai['total'] > 0) {
                    $status = true;
                    for ($i = $start; $i <= $end; $i = strtotime('+30 minutes', $i)) {
                        foreach ($avai['results'] as $item) {
                            if ($i >= $item->start_time && $i <= $item->end_time) {
                                $status = false;
                                break;
                            }
                        }
                        if (!$status) {
                            break;
                        }
                    }
                    if (!$status) {
                        return [
                            'status' => 0,
                            'message' => __('This date range is not available')
                        ];
                    }
                }
            } else {
                return [
                    'status' => 0,
                    'message' => __('Please select a valid datetime')
                ];
            }

        }


        return [
            'status' => 1,
            'message' => __('This date range is available')
        ];
    }

    public function _getHomePriceRealTime(Request $request)
    {
        $homeID = request()->get('homeID');
        $homeEncrypt = request()->get('homeEncrypt');

        $startDate = strtotime(request()->get('checkIn'));
        $endDate = strtotime(request()->get('checkOut'));

        $startTime = request()->get('startTime');
        $endTime = request()->get('endTime');

        $number_adults = (int)request()->get('num_adults');
        $number_children = (int)request()->get('num_children');

        $extraServices = request()->get('extraServices');
        $total = 0;
        if (hh_compare_encrypt($homeID, $homeEncrypt) && $startDate && $endDate) {
            $home = $this->getById($homeID);
            if ($home->booking_type == 'per_night') {
                // $homePriceModel = new HomePrice();
                // $checkRentValidate = $homePriceModel->getPriceItems($homeID, $startDate, $endDate, 'on');
                // if($checkRentValidate['total'] > 0){
                //     $numberNight = hh_date_diff($startDate, $endDate);
                //     $extra = $this->getExtraPrice($home, $extraServices, $numberNight);
                //     $total = $checkRentValidate['results'][0]->price_per_night * $numberNight + $extra;
                // }else {
                //     $numberNight = hh_date_diff($startDate, $endDate);
                //     $price = $this->getRealPrice($home, $startDate, $endDate, $number_adults + $number_children);
                //     $requiredExtra = $this->getRequiredExtraPrice($home, $numberNight);
                //     $extra = $this->getExtraPrice($home, $extraServices, $numberNight);
                //     $total = $price + $requiredExtra + $extra;
                // }
                $numberNight = hh_date_diff($startDate, $endDate);
                $price = $this->getRealPrice($home, $startDate, $endDate, $number_adults + $number_children);
                $requiredExtra = $this->getRequiredExtraPrice($home, $numberNight);
                $extra = $this->getExtraPrice($home, $extraServices, $numberNight);
                $total = $price + $requiredExtra + $extra;
            } elseif ($home->booking_type == 'per_hour') {
                $startTime = strtotime(date('Y-m-d', $startDate) . ' ' . $startTime);
                $endTime = strtotime(date('Y-m-d', $endDate) . ' ' . $endTime);
                if (is_timestamp($startTime) && is_timestamp($endTime) && $startTime < $endTime) {
                    $numberNight = hh_date_diff($startTime, $endTime, 'hour');
                    $price = $this->getRealPriceByTime($home, $startTime, $endTime, $number_adults + $number_children);
                    $requiredExtra = $this->getRequiredExtraPrice($home, $numberNight);
                    $extra = $this->getExtraPrice($home, $extraServices, $numberNight);
                    $total = $price + $requiredExtra + $extra;
                } else {
                    $this->sendJson([
                        'status' => 0,
                        'html' => '',
                        'message' => __('The data is invalid')
                    ], true);
                }
            };
            $this->sendJson([
                'status' => 1,
                'html' => view('frontend.home.calculate-price-render', ['total' => $total])->render()
            ], true);
        } else {
            $this->sendJson([
                'status' => 0,
                'html' => '',
                'message' => __('The data is invalid')
            ], true);
        }
    }

    public function _getHomeAvailabilitySingle(Request $request)
    {
        $events['events'] = [];
        $startTime = strtotime(request()->get('startTime'));
        $endTime = strtotime(request()->get('endTime'));
        $homeID = request()->get('homeID');
        $homeEncrypt = request()->get('homeEncrypt');

        if ($startTime && $endTime && hh_compare_encrypt($homeID, $homeEncrypt)) {
            $price_model = new HomePrice();
            $avai_model = new HomeAvailability();
            $priceItems = $price_model->getPriceItems($homeID, $startTime, $endTime, $status = 'on');
            $homeObject = $this->getById($homeID);

            $price = (float)$homeObject->base_price;
            $wprice = $homeObject->weekend_price;
            $ruleWeekend = $homeObject->weekend_to_apply;
            if ($homeObject->booking_type == 'per_night') {
                $endTime = strtotime('-1 day', $endTime);
                $avaiItems = $avai_model->getAvailabilityItems($homeID, $startTime, $endTime);
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
                            // if ($i >= $range->start_time && $i <= $range->end_time) {

                            //     if ($range->price != 0){
                            //         $event = convert_price($range->price);
                            //     }else if(){
                            //         $event = convert_price($range->price_per_night);
                            //     }
                            //     $inCustom = true;
                            //     break;
                            // }
                            if ($i >= $range->start_time && $i <= $range->end_time) {
                                if($range->first_minute == 'on' || $range->last_minute == 'on') {
                                    $event = (float)$price - ($price * ($range->discount_percent) / 100);
                                }else if($range->price == 0 && $range->price_per_night > 0){
                                    $event = (float)$range->price_per_night;
                                }else {
                                    $event = (float)$range->price_per_night;
                                }
                                $event = convert_price($event);
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
                $avaiItems = $avai_model->getAvailabilityTimeItems($homeID, $startTime, $endTime);
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

        }

        $this->sendJson($events, true);
    }

    public function _getHomeAvailabilityTimeSingle(Request $request)
    {
        $date = request()->get('start');
        $home_id = request()->get('home_id');

        if ($date && $home_id) {
            $home = get_post($home_id, 'home');
            $start_time = (!empty($home->checkin_time)) ? $home->checkin_time : '12:00 AM';
            $end_time = (!empty($home->checkout_time)) ? $home->checkout_time : '11:30 PM';
            $start_date = strtotime($date . ' ' . $start_time);
            $end_date = strtotime($date . ' ' . $end_time);
            $avai_model = new HomeAvailability();
            $calendarItems = $avai_model->getAvailabilityItems($home_id, strtotime($date), strtotime($date));
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

    public function _getHomeSingle(Request $request, $home_id, $home_name = null)
    {
        $homeObject = $this->getById($home_id, true);

        if (is_null($homeObject) || !$homeObject || $homeObject->status != 'publish') {
            return view('frontend.404');
        } else {
            return view('frontend.home.default');
        }
    }

    public function _myHome(Request $request, $page = 1)
    {
        $folder = $this->getFolder();

        $search = request()->get('_s');
        $orderBy = request()->get('orderby', 'post_id');
        $order = request()->get('order', 'desc');
        $booking_type = request()->get('booking_type');
        $status = request()->get('status');

        $allHomes = $this->getAllHomes([
            'search' => $search,
            'orderby' => $orderBy,
            'order' => $order,
            'booking_type' => $booking_type,
            'status' => $status,
            'page' => $page
        ]);

        return view("dashboard.screens.{$folder}.services.home.my-home", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'allHomes' => $allHomes]);
    }

    public function _deleteHomeItem(Request $request)
    {
        $home_id = request()->get('serviceID');
        $home_encrypt = request()->get('serviceEncrypt');

        if (!hh_compare_encrypt($home_id, $home_encrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('The home is not exist')
            ], true);
        }

        $home_model = new Home();

        $delete = $home_model->deleteHomeItem($home_id);
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
            'message' => __('Has error when delete this home')
        ], true);
    }

    public function _changeStatusHome(Request $request)
    {
        $home_id = request()->get('serviceID');
        $home_encrypt = request()->get('serviceEncrypt');
        $status = request()->get('status', '');

        if (!hh_compare_encrypt($home_id, $home_encrypt) || !$status) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('The data is invalid')
            ], true);
        }

        $home_model = new Home();
        $updated = $home_model->updateStatus($home_id, $status);
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

    public function _updateHome(Request $request)
    {
        $step = request()->get('step', 'next');
        $event = request()->get('option_event', 'button');
        $redirect = request()->get('redirect');
        $post_title_field = 'post_title';
        if (is_multi_language()) {
            $current_lang = get_current_language();
            $post_title_field .= '_' . $current_lang;
        }

        $facilities = get_terms('home-facilities');
        $tmp = array();
        foreach ($facilities as $key => $value) {
            $idName = str_replace(' ', '-', str_replace(['[', ']'], '_', strtolower($value['title'])));
            // print_r($_POST['bed-room']);
            // exit;
            // if(in_array($idName, $_POST)) {
            if(isset($_POST[$idName])){
                $tmp[$value['title']] = $_POST[$idName];
                unset($_POST[$idName]);
            }else {
                $tmp[$value['title']] = null;
            }
            // $tmp[$value['title']] = request()->get($idName);
        }

        $distance = get_terms('home-distance');
        $distance_tmp = array();
        foreach ($distance as $key => $value) {
            $idName = 'distance_'.str_replace(' ', '-', str_replace(['[', ']'], '_', strtolower($value)));
            if(isset($_POST[$idName])){
                $distance_tmp[$value] = $_POST[$idName];
            }else {
                $distance_tmp[$value] = null;
            }
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

            $home = new Home();
            $homeObject = $home->getById($postID);
            if (!is_object($homeObject)) {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('This home is not available')
                ], true);
            }

            $data = [];

            if ($homeObject->status == 'revision') {
                $data['status'] = 'pending';
            }

            $data['facilities'] = json_encode($tmp);
            $data['distance'] = json_encode($distance_tmp);
            foreach ($fields as $field) {
                $field = \ThemeOptions::mergeField($field);
                $value = \ThemeOptions::fetchField($field);
                if (!$field['excluded'] && !empty($value)) {
                    if ($field['field_type'] == 'meta') {
                        $data[$field['id']] = $value;
                    } elseif ($field['field_type'] == 'taxonomy') {
                        if(explode(':', $field['choices'])[1] != 'home-distance' && explode(':', $field['choices'])[1] != 'home-facilities'){
                            $value = (array)$value;
                            $taxonomy = explode(':', $field['choices'])[1];
                            $termRelation = new TermRelation();
                            $termRelation->deleteRelationByServiceID($postID, $taxonomy);
                            foreach ($value as $termID) {
                                $termRelation->createRelation($termID, $postID, 'home');
                            }
                            $data[$field['id']] = implode(',', $value);
                        }
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
                    $data['post_slug'] = Str::slug(esc_html(request()->get($post_title_field, 'new-home-' . time())));
                }
                $home = new Home();
                $home->updateHome($data, $postID);
            }
            do_action('hh_saved_home_meta', $postID);
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

    public function _editHome(Request $request, $id = null)
    {
        $folder = $this->getFolder();
        $home = new Home();
        $homeObject = $home->getById($id);
        if (is_object($homeObject) && user_can_edit_service($homeObject)) {
            return view("dashboard.screens.{$folder}.services.home.edit-home", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'newHome' => $id]);

        } else {
            return redirect()->to(dashboard_url('my-home'));
        }
    }

    public function _addNewHome(Request $request)
    {
        $folder = $this->getFolder();
        $home = new Home();
        $newHome = $home->createHome();
        return view("dashboard.screens.{$folder}.services.home.add-new-home", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'newHome' => $newHome]);
    }

    public function _duplicateHome(Request $request)
    {
        $home_id = request()->get('serviceID');
        $home_encrypt = request()->get('serviceEncrypt');

        if (!hh_compare_encrypt($home_id, $home_encrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('The home is not exist')
            ], true);
        }

        $home = new Home();
        $new_home = $home->duplicate($home_id);
        if ($new_home) {
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Duplicated new home successful'),
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

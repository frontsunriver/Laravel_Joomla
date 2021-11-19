<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Option;
use Illuminate\Http\Request;
use Sentinel;

class OptionController extends Controller
{
    private $settings_value = [];

    public function _getAvailabilityTimeSlot(Request $request)
    {
        $service_type = request()->get('service_type', 'home');

        $func = '_' . $service_type . '_availability_time_slot';
        return $this->sendJson([
            'status' => 1,
            'html' => $this->$func()
        ]);
    }

    private function _car_availability_time_slot()
    {
        $service_id = request()->get('service_id');
        $start_date = request()->get('start_date');
        $booking = new Booking();
        $list = $booking->getBookingHourItems($service_id, $start_date, 'car');
        $html = '<table class="table">
        <thead class="thead-light">
        <tr>
            <th>' . __('Buyer') . '</th>
            <th>' . __('From') . '</th>
            <th>' . __('To') . '</th>
            <th>' . __('Number') . '</th>
            <th>' . __('Status') . '</th>
        </tr>
        </thead>
        <tbody>
        ';
        if ($list['total']) {
            foreach ($list['results'] as $item) {
                $avatar = get_user_avatar($item->buyer, [50, 50]);
                $name = get_username($item->buyer);

                $bookingStatus = booking_status_info($item->status);
                $html .= '
                    <tr>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <img src="' . esc_attr($avatar) . '" alt="' . __('User Avatar') . '" class="avatar rounded-circle mr-1" style="max-width: 35px">
                                <h5 class="h6">' . esc_html($name) . '</h5>
                            </div>
                        </td>';
                $html .= '<td class="align-middle">
                            <p class="item mb-0 c-black"><strong>' . date(hh_time_format() . ' ' . hh_date_format(), $item->start_time) .
                    '</p>
                        </td>';
                $html .= '<td class="align-middle">
                            <p class="item mb-0 c-black"><strong>' . date(hh_time_format() . ' ' . hh_date_format(), $item->end_time) .
                    '</p>
                        </td>';
                $html .= '<td class="align-middle text-center">
                            ' . e($item->number) . '
                         </td>
                         <td class="align-middle text-center">
                            <div class="booking-status ' . e($item->status) . ' booking-icon"
                                 data-toggle="tooltip" data-placement="right" title=""
                                 title="' . e($bookingStatus['label']) . '"><span
                                        class="exp d-none">' . e($bookingStatus['label']) . '</span>
                            </div>
                        </td>
                    </tr>
                ';
            }
        } else {
            $html .= '<tr><th colspan="3"><h4>' . __('No items found yet') . '</h4></th></tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }

    private function _experience_availability_time_slot()
    {
        $service_id = request()->get('service_id');
        $start_date = request()->get('start_date');
        $experience = get_post($service_id, 'experience');
        $booking = new Booking();
        $list = $booking->getBookingHourItems($service_id, $start_date, 'experience');
        $html = '<table class="table">
        <thead class="thead-light">
        <tr>
            <th>' . __('Buyer') . '</th>';
        if ($experience->booking_type == 'just_date') {
            $html .= '<th>' . __('Date') . '</th>';
        } else {
            $html .= '<th>' . __('Time') . '</th>';
        }

        $html .= '
            <th class="text-center">' . __('Guest') . '</th>
            <th>' . __('Status') . '</th>
        </tr>
        </thead>
        <tbody>
        ';
        if ($list['total']) {
            foreach ($list['results'] as $item) {
                $avatar = get_user_avatar($item->buyer, [50, 50]);
                $name = get_username($item->buyer);

                $bookingStatus = booking_status_info($item->status);
                $html .= '
                    <tr>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <img src="' . esc_attr($avatar) . '" alt="' . __('User Avatar') . '" class="avatar rounded-circle mr-1" style="max-width: 35px">
                                <h5 class="h6">' . esc_html($name) . '</h5>
                            </div>
                        </td>';
                if ($experience->booking_type == 'just_date') {
                    $html .= '<td class="align-middle">
                                <p class="item mb-0 c-black"><strong>' . date(hh_date_format(), $item->start_time) .
                        '</p>
                                </td>';
                } else {
                    $html .= '<td class="align-middle">
                                <p class="item mb-0 c-black"><strong>' . date(hh_time_format(), $item->start_time) .
                        '</p>
                                </td>';
                }
                $html .= '<td class="align-middle text-center">
                            ' . e($item->number_of_guest) . '
                         </td>
                         <td class="align-middle text-center">
                            <div class="booking-status ' . e($item->status) . ' booking-icon"
                                 data-toggle="tooltip" data-placement="right" title=""
                                 title="' . e($bookingStatus['label']) . '"><span
                                        class="exp d-none">' . e($bookingStatus['label']) . '</span>
                            </div>
                        </td>
                    </tr>
                ';
            }
        } else {
            $html .= '<tr><th colspan="3"><h4>' . __('No items found yet') . '</h4></th></tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }

    private function _home_availability_time_slot()
    {
        $service_id = request()->get('service_id');
        $start_date = request()->get('start_date');

        $booking = new Booking();
        $list = $booking->getBookingHourItems($service_id, $start_date, 'home');
        $html = '<table class="table">
        <thead class="thead-light">
        <tr>
            <th>' . __('Buyer') . '</th>
            <th>' . __('Time') . '</th>
            <th>' . __('Status') . '</th>
        </tr>
        </thead>
        <tbody>
        ';
        if ($list['total']) {
            foreach ($list['results'] as $item) {
                $avatar = get_user_avatar($item->buyer, [50, 50]);
                $name = get_username($item->buyer);

                $bookingStatus = booking_status_info($item->status);
                $html .= '
                    <tr>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <img src="' . esc_attr($avatar) . '" alt="' . __('User Avatar') . '" class="avatar rounded-circle mr-1" style="max-width: 35px">
                                <h5 class="h6">' . esc_html($name) . '</h5>
                            </div>
                        </td>
                        <td class="align-middle">
                            <p class="item mb-0 c-black"><strong>' .
                    date(hh_time_format(), $item->start_time) . '<span class="ml-1 mr-1">' . get_icon('002_right_arrow', '#6c757d', '14px') . '</span>' .
                    date(hh_time_format(), $item->end_time) .
                    '</p>
                        </td>
                        <td class="align-middle text-center">
                            <div class="booking-status ' . e($item->status) . ' booking-icon"
                                 data-toggle="tooltip" data-placement="right" title=""
                                 title="' . e($bookingStatus['label']) . '"><span
                                        class="exp d-none">' . e($bookingStatus['label']) . '</span>
                            </div>
                        </td>
                    </tr>
                ';
            }
        } else {
            $html .= '<tr><th colspan="3"><h4>' . __('No items found yet') . '</h4></th></tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }

    public function _getInventory(Request $request)
    {
        $service_type = request()->get('service_type', 'home');
        $func = '_' . $service_type . '_inventory';
        $events = $this->$func();

        return $this->sendJson($events);
    }

    private function _experience_inventory()
    {
        $post_id = request()->get('post_id');
        $start = strtotime(request()->get('start'));
        $end = strtotime(request()->get('end'));

        $list_date = [];

        if ($post_id && $start && $end) {
            $booking = new Booking();
            $list_booking = $booking->getBookingItems($post_id, $start, $end, true, 'experience');

            if ($list_booking['total']) {
                foreach ($list_booking['results'] as $item) {
                    $params = [
                        'service_id' => $post_id,
                        'service_type' => 'experience',
                        'start_date' => $item->start_date
                    ];
                    $event = '<div class="booking-item booking-item-time" data-booking-id="' . e($item->booking_id) . '">' . '<a class="item"
                    data-title="' . sprintf(__('Reservations on %s'), date(hh_date_format(), $item->start_date)) . '"
                    data-toggle="modal"
                    data-params="' . base64_encode(json_encode($params)) . '"
                    data-target="#hh-show-availability-time-slot-modal">' . _n("[0::%s bookings][1::%s booking][2::%s bookings]", $item->total_item) . '</a>' . '</div>';
                    $events['events'][] = [
                        'start' => date('Y-m-d', $item->start_date),
                        'end' => date('Y-m-d', $item->end_date),
                        'event' => $event,
                        'status' => 'not_available',
                        'group' => true,
                        'extra_class' => $item->status
                    ];
                    for ($i = $item->start_date; $i <= $item->end_date; $i = strtotime('+1 day', $i)) {
                        $list_date[] = $i;
                    }
                }
            }
            for ($i = $start; $i <= $end; $i = strtotime('+1 day', $i)) {
                if (!in_array($i, $list_date)) {
                    $events['events'][] = [
                        'start' => date('Y-m-d', $i),
                        'end' => date('Y-m-d', $i),
                        'event' => '',
                        'status' => 'not_available'
                    ];
                }
            }
        }

        return $events;
    }

    private function _car_inventory()
    {
        $post_id = request()->get('post_id');
        $start = strtotime(request()->get('start'));
        $end = strtotime(request()->get('end'));

        $list_date = [];

        if ($post_id && $start && $end) {
            $booking = new Booking();
            $list_booking = $booking->getBookingItems($post_id, $start, $end, true, 'car');

            if ($list_booking['total']) {
                foreach ($list_booking['results'] as $item) {
                    $params = [
                        'service_id' => $post_id,
                        'service_type' => 'car',
                        'start_date' => $item->start_date
                    ];
                    $event = '<div class="booking-item booking-item-time" data-booking-id="' . e($item->booking_id) . '">' . '<a class="item"
                    data-title="' . sprintf(__('Reservations on %s'), date(hh_date_format(), $item->start_date)) . '"
                    data-toggle="modal"
                    data-params="' . base64_encode(json_encode($params)) . '"
                    data-target="#hh-show-availability-time-slot-modal">' . _n("[0::%s bookings][1::%s booking][2::%s bookings]", $item->total_item) . '</a>' . '</div>';
                    $events['events'][] = [
                        'start' => date('Y-m-d', $item->start_date),
                        'end' => date('Y-m-d', $item->start_date),
                        'event' => $event,
                        'status' => 'not_available',
                        'group' => false,
                        'extra_class' => $item->status
                    ];
                    $list_date[] = $item->start_date;
                }
            }
            for ($i = $start; $i <= $end; $i = strtotime('+1 day', $i)) {
                if (!in_array($i, $list_date)) {
                    $events['events'][] = [
                        'start' => date('Y-m-d', $i),
                        'end' => date('Y-m-d', $i),
                        'event' => '',
                        'status' => 'not_available'
                    ];
                }
            }
        }

        return $events;
    }

    private function _home_inventory()
    {
        $events['events'] = [];
        $post_id = request()->get('post_id');
        $start = strtotime(request()->get('start'));
        $end = strtotime(request()->get('end'));
        $homeObject = get_post($post_id, 'home');
        $booking_type = $homeObject->booking_type;
        $list_date = [];
        if ($post_id && $start && $end) {
            $booking = new Booking();
            if ($booking_type == 'per_night') {
                $list_booking = $booking->getBookingItems($post_id, $start, $end, false, 'home');
            } else {
                $list_booking = $booking->getBookingItems($post_id, $start, $end, true, 'home');
            }

            if ($list_booking['total']) {
                foreach ($list_booking['results'] as $item) {
                    if ($booking_type == 'per_night') {
                        $avatar = get_user_avatar($item->buyer, [50, 50]);
                        $event = '<div class="booking-item booking-' . e($item->booking_id) . '"><div class="author"><img class="img-fluid" src="' . e($avatar) . '" alt="Avatar"></div></div>';
                    } else {
                        $params = [
                            'service_id' => $post_id,
                            'service_type' => 'home',
                            'start_date' => $item->start_date
                        ];
                        $event = '<div class="booking-item booking-item-time" data-booking-id="' . e($item->booking_id) . '">' . '<a class="item"
                        data-title="' . sprintf(__('Reservations on %s'), date(hh_date_format(), $item->start_date)) . '"
                        data-toggle="modal"
                        data-params="' . base64_encode(json_encode($params)) . '"
                        data-target="#hh-show-availability-time-slot-modal">' . _n("[0::%s bookings][1::%s booking][2::%s bookings]", $item->total_item) . '</a>' . '</div>';
                    }
                    $events['events'][] = [
                        'start' => date('Y-m-d', $item->start_date),
                        'end' => date('Y-m-d', $item->end_date),
                        'event' => $event,
                        'status' => 'not_available',
                        'group' => true,
                        'extra_class' => $item->status
                    ];
                    for ($i = $item->start_date; $i <= $item->end_date; $i = strtotime('+1 day', $i)) {
                        $list_date[] = $i;
                    }
                }
            }
            for ($i = $start; $i <= $end; $i = strtotime('+1 day', $i)) {
                if (!in_array($i, $list_date)) {
                    $events['events'][] = [
                        'start' => date('Y-m-d', $i),
                        'end' => date('Y-m-d', $i),
                        'event' => '',
                        'status' => 'not_available'
                    ];
                }
            }
        }

        return $events;
    }

    public function _setFeaturedImage(Request $request)
    {
        $id = request()->get('id');
        $postID = request()->get('postID');
        $postType = request()->get('postType', 'home');
        $size = explode(',', request()->get('size', [450, 320]));
        if ($postID && $id) {
            set_post_thumbnail($postID, $id, $postType);
            $url = get_attachment_url($id, $size);
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Successfully'),
                'img' => $url
            ], true);
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('The data is invalid')
            ], true);
        }
    }

    public function _saveQuickSetting(Request $request)
    {
        $option = new Option();
        $hasOption = $option->getOption(\ThemeOptions::getOptionID());
        $optionValue = (!is_null($hasOption)) ? unserialize($hasOption->option_value) : [];
        $all_fields = request()->get('all_fields');
        $all_fields = json_decode(base64_decode($all_fields), true);
        if ($all_fields && is_array($all_fields)) {
            foreach ($all_fields as $field) {
                $value = \ThemeOptions::fetchField($field);
                if (!empty($value)) {
                    $optionValue[$field['id']] = $value;
                } else {
                    if (isset($optionValue[$field['id']])) {
                        unset($optionValue[$field['id']]);
                    }
                }
            }
        }
        $optionValue = serialize($optionValue);
        if ($hasOption) {
            $option->updateOption(\ThemeOptions::getOptionID(), $optionValue);
        } else {
            $option->createOption(\ThemeOptions::getOptionID(), $optionValue);
        }

        return $this->sendJson([
            'status' => 1,
            'message' => __('Updated successfully')
        ]);
    }

    public function _saveSetting(Request $request)
    {
        $fields = request()->get('currentOptions', '');
        $fields = unserialize(base64_decode($fields));
        if ($fields) {
            $option = new Option();
            $hasOption = $option->getOption(\ThemeOptions::getOptionID());
            $optionValue = (!is_null($hasOption)) ? unserialize($hasOption->option_value) : [];
            foreach ($fields as $field) {
                $field = \ThemeOptions::mergeField($field);
                if ($field['type'] == 'payment') {
                    $all_payments = get_payments();
                    if ($all_payments) {
                        foreach ($all_payments as $key => $class) {
                            $p_options = $class::getOptions()['content'];
                            if ($p_options) {
                                foreach ($p_options as $_option) {
                                    $_option = \ThemeOptions::mergeField($_option);
                                    if (isset($_option['translation']) && $_option['translation']) {
                                        $value = set_translate($_option['id']);
                                    } else {
                                        $value = \ThemeOptions::fetchField($_option);
                                    }
                                    $optionValue[$_option['id']] = $value;
                                }
                            }
                        }
                    }
                } else {
                    $value = \ThemeOptions::fetchField($field);
                    $optionValue[$field['id']] = $value;
                }
            }
            $optionValue = serialize($optionValue);
            if ($hasOption) {
                $updated = $option->updateOption(\ThemeOptions::getOptionID(), $optionValue);
            } else {
                $updated = $option->createOption(\ThemeOptions::getOptionID(), $optionValue);
            }
            if (!is_null($updated)) {
                do_action('hh_updated_option', $optionValue);
                $this->sendJson([
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Saved successfully')
                ], true);
            }
        }
        $this->sendJson([
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Have error when saving data')
        ], true);
    }

    private function renderSettingsValue($type, $data)
    {
        $name = $data['id'];
        switch ($type) {
            case 'list_item':
                $post_data = request()->get($name, '');
                $arr_item = [];
                if (!empty($data['items'])) {
                    foreach ($data['items'] as $k => $v) {
                        array_push($arr_item, $v['id']);
                    }
                }

                if (!empty($post_data[$arr_item[0]])) {
                    foreach ($post_data[$arr_item[0]] as $k => $v) {
                        for ($j = 0; $j < count($arr_item); $j++) {
                            if ($j == 0) {
                                if (isset($arr_item[$j])) {
                                    $arr[$k][$arr_item[$j]] = $v;
                                }
                            } else {
                                if (isset($post_data[$arr_item[$j]])) {
                                    $arr[$k][$arr_item[$j]] = $post_data[$arr_item[$j]][$k];
                                }
                            }
                        }
                    }
                }

                $this->settings_value[$name] = $arr;
                break;
            default:
                $post_data = request()->get($name, '');
                $this->settings_value[$name] = $post_data;
                break;
        }
    }

    public function _getSetting()
    {
        $folder = $this->getFolder();
        return view("dashboard.screens.{$folder}.setting", ['role' => $folder, 'bodyClass' => 'hh-dashboard']);
    }

    public function _getListItem(Request $request)
    {
        return \ThemeOptions::getListItem($request);
    }

}

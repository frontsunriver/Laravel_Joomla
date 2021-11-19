<?php
$car_text = _n("[0::%s Cars][1::%s Car][2::%s Cars]", $count);
$address_text = '';
if (!empty($address)) {
    $address_text = sprintf(__('in <strong>%s</strong>'), urldecode(esc_html($address)));
}

$booking_type = get_car_booking_type();

$date_string = '';
if (!empty($check_in) && !empty($check_out)) {
    if ($booking_type == 'hour') {
        $check_in_time_str = strtolower(urldecode($check_in_time));
        $check_out_time_str = strtolower(urldecode($check_out_time));

        $date_string = sprintf(__('from <strong>%s</strong> to <strong>%s</strong>'), hh_get_date_from_request(esc_html($check_in)) . ' ' . esc_html($check_in_time_str), hh_get_date_from_request(esc_html($check_out)) . ' ' . esc_html($check_out_time_str));
    } else {
        $date_string = sprintf(__('from <strong>%s</strong> to <strong>%s</strong>'), hh_get_date_from_request(esc_html($check_in)), hh_get_date_from_request(esc_html($check_out)));
    }
}

?>
{{__('Found')}} <span class="item-found"><?php echo balanceTags($car_text); ?></span> <?php echo balanceTags($address_text . ' ' . $date_string) ?>

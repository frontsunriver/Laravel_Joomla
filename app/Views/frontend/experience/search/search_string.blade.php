<?php
$experience_text = _n("[0::%s Experiences][1::%s Experience][2::%s Experience]", $count);
$address_text = '';
if (!empty($address)) {
    $address_text = sprintf(__('in <strong>%s</strong>'),urldecode(esc_html($address)));
}

$date_string = '';
if (!empty($check_in) && !empty($check_out)) {
    $date_string = sprintf(__('from <strong>%s</strong> to <strong>%s</strong>'), hh_get_date_from_request(esc_html($check_in)), hh_get_date_from_request(esc_html($check_out)));
}

?>
{{__('Found')}} <span
    class="item-found"><?php echo balanceTags($experience_text); ?></span> <?php echo balanceTags($address_text . ' ' . $date_string) ?>

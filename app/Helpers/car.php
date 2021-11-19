<?php
use App\Models\Car;

function get_date_query_string($request){
	$params = ['checkInOut', 'checkIn', 'checkOut', 'checkInTime', 'checkOutTime'];
	if(!empty($request)){
		foreach ($request as $key => $val){
			if(!in_array($key, $params)){
				unset($request[$key]);
			}
		}
		if(!empty($request)){
			return '?'.build_query($request);
		}
	}
	return '';
}

function get_car_booking_type(){
	$booking_type = get_option('car_booking_type', 'day');
	return $booking_type;
}

function get_car_unit()
{
	$booking_type = get_option('car_booking_type', 'day');
	if($booking_type == 'hour') {
		return __( 'hour' );
	}else{
		return __( 'day' );
	}
}

function get_equipments($equipments, $tax_equipment){
	$res = [];
	$termData = [];
	if(count($tax_equipment) > 0){
		if(!empty($tax_equipment)){
			foreach ($tax_equipment as $term){
				$termData[$term->term_id] = [
					'title' => $term->term_title,
					'price' => $term->term_price
				];
			}
		}
	}
	if(!empty($termData)) {
		if ( ! empty( $equipments ) ) {
			$equipments = maybe_unserialize( $equipments );
			if ( ! empty( $equipments ) && is_array($equipments)) {
				foreach ( $equipments as $key => $val ) {
					if(isset($termData[$key]) && $val['choose'] == 'yes'){
						$title = $termData[$key]['title'];
						$price = (float)$val['price'];
						if(!$val['custom']){
							$price = (float)$termData[$key]['price'];
						}
						$res[$key] = [
							'title' => $title,
							'price' => $price
						];
					}
				}
			}
		}
	}
	return $res;
}

function count_car_in_car_type($car_type_id)
{
	$model = new Car();
	return $model->countCarInCarType($car_type_id);
}

function get_car_min_max_price(){
    return \App\Controllers\Services\CarController::get_inst()->getMinMaxPrice();
}

function get_car_terms_filter()
{
    $res = [];
    $filter_type = [
        'car-type' => __('Car Type'),
        'car-feature' => __('Car Features')
    ];
    foreach ($filter_type as $k => $v) {
        $res[$k] = [
            'label' => $v,
            'items' => get_terms($k)
        ];
    }
    return $res;
}

function get_car_permalink($post_id, $post_slug = ''){
    return get_the_permalink($post_id, $post_slug, 'car');
}

function get_car_search_page($params = '')
{
    return url(Config::get('awebooking.post_types')['car']['search_slug'] . '/' . $params);
}

function get_car_thumbnail_id($car_id)
{
    if(!is_object($car_id)){
        $car_object = new Car();
        $car_id = $car_object->getById($car_id);
    }
    if(is_object($car_id)){

        return (isset($car_id->thumbnail_id) && $car_id->thumbnail_id) ? $car_id->thumbnail_id : false;
    }

    return false;
}
function has_car_thumbnail($car_id)
{
    if (!is_object($car_id)) {
        $car_object = new Car();
        $car_id = $car_object->getById($car_id);
    }
    if(is_object($car_id)){
        return isset($car_id->thumbnail_id) && $car_id->thumbnail_id;
    }
    return false;

}

<?php

namespace App\Controllers\Api;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends APIController
{
	public function __construct() {

	}

    public function getBookingDetail($token_code){
		if($token_code){
			$model = new Booking();
			$booking = $model->getBookingByToken($token_code);
			if($booking){
				return $this->sendJson([
					'status' => 1,
					'message' => __('Success'),
					'data' => $booking
				]);
			}
		}
		return $this->sendJson([
			'status' => 1,
			'message' => __('Data is invalid')
		]);
    }

    public function checkout(Request $request){
		$token = $request->bearerToken();
		$user = get_user_by_access_token($token);
		if($user){
			$rules = [
				'first_name' => 'required|string',
				'last_name' => 'required|string',
				'email' => 'required|email',
				'phone' => 'required|string',
				'address' => 'required|string',
				'payment' => 'required|string',
				'payment_status' => 'required|string'
			];

			$validator = Validator::make( $request->all(), $rules );
			if ( $validator->fails() ) {
				return $this->sendJson( [
					'status'  => 0,
					'message' => $validator->errors()
				] );
			}

			$paymentMethod = request()->get('payment');
			$paymentStatus = request()->get('payment_status');
			$payment = get_available_payments($paymentMethod);
			if (!$payment) {
				return $this->sendJson([
					'status' => 0,
					'message' => __('This payment gateway is not available')
				]);
			}

			if(!in_array($paymentStatus, ['completed', 'incomplete', 'pending', 'cancelled'])){
				return $this->sendJson([
					'status' => 0,
					'message' => __('Payment status is invalid')
				]);
			}

			$user_id = $user->id;

			$cart = get_user_meta($user_id, 'cart_data');
			if(!$cart){
				return $this->sendJson([
					'status' => 0,
					'message' => __('Cart is empty')
				]);
			}

			$cart = json_decode($cart, true);
			$new_booking_id = $this->createBooking($user_id, $cart);

			if (!$new_booking_id) {
				return $this->sendJson([
					'status' => 0,
					'message' => __('Can not create new booking. Please try again!')
				]);
			}

			do_action('hh_after_create_booking', $new_booking_id);

			if ($paymentStatus == 'pending') {
				\App\Controllers\BookingController::get_inst()->deleteBooking($new_booking_id);
				return $this->sendJson([
					'status' => 0,
					'message' => __('The booking has not been completed')
				]);
			} else {
				\App\Controllers\BookingController::get_inst()->updateBookingStatus($new_booking_id, $paymentStatus, true);

				do_action('hh_after_created_booking', $new_booking_id, $paymentStatus);

				$booking_object = get_booking($new_booking_id, $cart['serviceType']);

				remove_user_meta($user_id, 'cart_data');

				return $this->sendJson([
					'status' => 1,
					'message' => __('Successful booking'),
					'token_code' => $booking_object->token_code
				]);
			}
		}
		return $this->sendJson([
			'status' => 0,
			'message' => __('Data is invalid')
		]);
    }

    public function createBooking($user_id, $cart){
	    $paymentMethod = request()->post('payment');
	    $payment = get_available_payments($paymentMethod);
	    $serviceObject = unserialize($cart['serviceObject']);

	    $user_data = [
		    'email' => request()->post('email'),
		    'firstName' => request()->post('first_name'),
		    'lastName' => request()->post('last_name'),
		    'phone' => request()->post('phone'),
		    'address' => request()->post('address'),
		    'city' => request()->post('city'),
		    'postCode' => request()->post('post_code'),
		    'country' => request()->post('country'),
	    ];

	    $cart['user_data'] = $user_data;
	    $total_minutes = 1440;
	    if (isset($serviceObject->booking_type) && $serviceObject->booking_type == 'per_hour') {
		    $total_minutes = hh_date_diff($cart['cartData']['startTime'], $cart['cartData']['endTime'], 'minute');
	    }

	    $created_at = time();
	    $data = [
		    'booking_id' => $cart['serviceID'] . $created_at,
		    'booking_description' => sprintf(__('Booking at %s'), $serviceObject->post_title),
		    'service_id' => $cart['serviceID'],
		    'service_type' => $cart['serviceType'],
		    'first_name' => request()->get('first_name', ''),
		    'last_name' => request()->get('last_name', ''),
		    'email' => request()->get('email', ''),
		    'phone' => request()->get('phone', ''),
		    'address' => request()->get('address', ''),
		    'note' => request()->get('note', ''),
		    'number_of_guest' => isset($cart['cartData']['numberGuest']) ? $cart['cartData']['numberGuest'] : 0,
		    'total' => $cart['amount'],
		    'token_code' => hh_encrypt($cart['serviceID'] . $created_at),
		    'currency' => serialize(\Currencies::get_inst()->currentCurrency()),
		    'buyer' => $user_id,
		    'owner' => $serviceObject->author,
		    'payment_type' => $payment::$paymentId,
		    'total_minutes' => $total_minutes,
		    'status' => 'pending',
		    'checkout_data' => base64_encode(serialize($cart)),
		    'number' => isset($cart['cartData']['number']) ? $cart['cartData']['number'] : 0,
		    'created_date' => $created_at
	    ];

	    if ($cart['serviceType'] == 'car') {
		    $data['start_date'] = strtotime(date('Y-m-d', $cart['cartData']['startDate']));
		    $data['end_date'] = strtotime(date('Y-m-d', $cart['cartData']['endDate']));
		    $data['start_time'] = strtotime(date('Y-m-d h:i a', $cart['cartData']['startDateTime']));
		    $data['end_time'] = strtotime(date('Y-m-d h:i a', $cart['cartData']['endDateTime']));
	    } else {
		    $data['start_date'] = strtotime(date('Y-m-d', $cart['cartData']['startDate']));
		    $data['end_date'] = strtotime(date('Y-m-d', $cart['cartData']['endDate']));
		    $data['start_time'] = $cart['cartData']['startTime'];
		    $data['end_time'] = $cart['cartData']['endTime'];
	    }

	    $booking_model = new Booking();

	    $new_booking_id = $booking_model->createBooking($data);

	    do_action('awebooking_created_new_booking', $new_booking_id, $data);

	    return $new_booking_id;
    }

    public function getPaymentGateways(){
	    $allPayment = get_available_payments();
	    $data = [];
	    if (!empty($allPayment)){
		    foreach ($allPayment as $key => $paymentName){
			    $data[] = [
			    	'id' => $paymentName::getID(),
				    'name' => $paymentName::getName(),
				    'description' => $paymentName::getDescription(),
				    'logo' => $paymentName::getLogo()
			    ];
		    }
	    }
	    return $this->sendJson([
	    	'status' => 1,
		    'message' => __('Success'),
		    'data' => $data
	    ]);
    }

    public function getCart(Request $request){
		$token = $request->bearerToken();
		$user = get_user_by_access_token($token);
		if($user){
			$cart = get_user_meta($user->id, 'cart_data');
			if($cart){
				return $this->sendJson([
					'status' => true,
					'message' => __('Success'),
					'data' => json_decode($cart)
				]);
			}else{
				return $this->sendJson([
					'status' => true,
                    'message' => __('Success'),
                    'data' => []
				]);
			}
		}
		return $this->sendJson([
			'status' => false,
			'message' => __('Data is invalid')
		]);
    }

    public function addToCart(Request $request){
	    $post_type = $request->post('post_type');
	    if(!empty($post_type) && in_array($post_type, ['home', 'experience', 'car'])){
			switch ($post_type){
				case 'home':
					return HomeController::inst()->addToCart($request);
					break;
				case 'experience':
					return ExperienceController::inst()->addToCart($request);
					break;
				case 'car':
					return CarController::inst()->addToCart($request);
					break;
			}
	    }
	    return $this->sendJson([
	    	'status' => false,
		    'message' => __('Post type is invalid')
	    ]);
    }

	public function getBookingHistory(){

    }
}

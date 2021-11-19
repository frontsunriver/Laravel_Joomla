<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/13/2019
 * Time: 10:05 AM
 */

class MailSystem
{
    public function __construct()
    {
        add_action('hh_after_created_booking', [$this, '_sendConfirmationEmail']);
        add_action('hh_after_created_booking', [$this, '_sendBookingDetailWhenCreateBooking']);
        add_action('hh_confirmed_booking', [$this, '_sendBookingDetail']);
        add_action('hh_change_booking_status', [$this, '_sendBookingDetailWhenChangeStatus'], 10, 3);
        add_action('hh_calculator_payout_each_partners', [$this, '_sendPayoutProcessing'], 10, 2);
        add_action('hh_change_payout_status', [$this, '_sendPayoutCompleted'], 10, 2);
        add_action('hh_registered_user', [$this, '_sendWelcomeUser'], 10, 2);
        add_action('hh_user_registered_as_partner', [$this, '_sendPartnerRequest'], 10, 2);
        add_action('hh_user_registered_as_customer', [$this, '_sendCustomerRequest'], 10, 1);
        add_action('hh_approved_partner', [$this, '_sendApprovedPartner'], 10, 2);
    }

    public function _sendApprovedPartner($user, $request)
    {
        $user_email = $user->email;
        if($request == 'request_a_partner'){
            $subject = sprintf(__('[%s] - Your partner registration request has been approved'), get_option('site_name'));
        }else{
            $subject = sprintf(__('[%s] - Your user registration request has been approved'), get_option('site_name'));
        }

        $content = view('frontend.email.user-approved', ['user' => $user, 'request' => $request])->render();
        $from = get_option('email_from_address');
        $from_name = get_option('email_from');
        send_mail($from, $from_name, $user_email, $subject, $content);
    }

    public function _sendWelcomeUser($user_id, $password)
    {
        $user = get_user_by_id($user_id);
        $user_email = $user->email;
        $subject = sprintf(__('[%s] - You have successfully created an account'), get_option('site_name'));
        $content = view('frontend.email.welcome-user', ['user' => $user, 'password' => $password])->render();
        $from = get_option('email_from_address');
        $from_name = get_option('email_from');
        send_mail($from, $from_name, $user_email, $subject, $content);
    }

    public function _sendCustomerRequest($user_id)
    {
        $user = get_user_by_id($user_id);
        $subject = sprintf(__('[%s] - A new customer registration needs approval'), get_option('site_name'));
        $content = view('frontend.email.customer-request', ['user' => $user])->render();
        $admin_data = get_admin_user();
        $admin_email = $admin_data->email;
        $from = get_option('email_from_address');
        $from_name = get_option('email_from');
        send_mail($from, $from_name, $admin_email, $subject, $content);
    }

    public function _sendPartnerRequest($user_id, $password)
    {

        $this->_sendWelcomeUser($user_id, $password);

        $user = get_user_by_id($user_id);
        $subject = sprintf(__('[%s] - A new registration partner needs approval'), get_option('site_name'));
        $content = view('frontend.email.partner-request', ['user' => $user])->render();
        $admin_data = get_admin_user();
        $admin_email = $admin_data->email;
        $from = get_option('email_from_address');
        $from_name = get_option('email_from');
        send_mail($from, $from_name, $admin_email, $subject, $content);
    }

    public function _sendPayoutCompleted($payout_id, $old_status)
    {
        $payout_model = new \App\Models\Payout();
        $payout_item = $payout_model->getPayout($payout_id);
        if (!is_null($payout_item)) {
            if ($payout_item->status == 'completed') {
                $userdata = get_user_by_id($payout_item->user_id);
                $payout_item = $payout_model->getPayout($payout_id);

                $user_email = $userdata->email;
                $date_payout = $payout_item->created;
                $subject = sprintf(__('[%s] - Your payout in %s has been confirmed and paid.'), get_option('site_name'), date('M Y', $date_payout));
                $content = view('frontend.email.payout-paid', ['payout_item' => $payout_item, 'userdata' => $userdata])->render();
                $from = get_option('email_from_address');
                $from_name = get_option('email_from');
                send_mail($from, $from_name, $user_email, $subject, $content);
            }
        }
    }

    public function _sendPayoutProcessing($user_id, $payout_id)
    {
        $userdata = get_user_by_id($user_id);
        $payout_model = new \App\Models\Payout();
        $payout_item = $payout_model->getPayout($payout_id);
        if ($payout_item && $userdata) {
            $user_email = $userdata->email;
            $date_payout = $payout_item->created;
            $subject = sprintf(__('[%s] - We are processing your payout in %s'), get_option('site_name'), date('M Y', $date_payout));
            $content = view('frontend.email.payout-processing', ['payout_item' => $payout_item, 'userdata' => $userdata])->render();
            $from = get_option('email_from_address');
            $from_name = get_option('email_from');
            send_mail($from, $from_name, $user_email, $subject, $content);
        }
    }

    public function _sendConfirmationEmail($booking_id)
    {
        if(get_option('enable_email_confirmation', 'on') == 'on'){
            reset_booking_data();
            $booking = get_booking($booking_id);
            if ($booking) {
                $customer_email = $booking->email;
                $subject = sprintf(__('[%s] Booking Confirmation'), get_option('site_name'));
                $content = view('frontend.email.confirmation', ['booking' => $booking])->render();

                $from = get_option('email_from_address');
                $from_name = get_option('email_from');
                send_mail($from, $from_name, $customer_email, $subject, $content);
                return true;
            }
        }
        return false;
    }

    public function _sendBookingDetailWhenCreateBooking($booking_id){
        if(get_option('enable_email_confirmation', 'on') == 'off'){
            $this->_sendBookingDetail($booking_id);
        }
    }

    public function _sendBookingDetailWhenChangeStatus($status, $booking_id, $created_booking)
    {
        if (!$created_booking) {
            $this->_sendBookingDetail($booking_id);
        }
    }

    public function _sendBookingDetail($booking_id)
    {
        reset_booking_data();
        $booking = get_booking($booking_id);
        if ($booking) {
            $from = get_option('email_from_address');
            $from_name = get_option('email_from');

            $service_type = $booking->service_type;

            $customer_email = $booking->email;
            $service_data = get_booking_data($booking->ID, 'serviceObject');
            $subject = sprintf(__('[%s] Your booking (%s) at %s'), get_option('site_name'), $booking->ID, get_translate($service_data->post_title));
            $content = view('frontend.email.' . $service_type . '.booking-detail', ['booking' => $booking, 'for' => 'customer'])->render();
            send_mail($from, $from_name, $customer_email, $subject, $content);


            $partner_data = get_user_by_id($booking->owner);
            $partner_email = $partner_data->email;
            $subject = sprintf(__('[%s] %s has booked your service - Booking ID: %s'), get_option('site_name'), $booking->first_name . ' ' . $booking->last_name, $booking->ID);
            $content = view('frontend.email.' . $service_type . '.booking-detail', ['booking' => $booking, 'for' => 'partner'])->render();
            send_mail($from, $from_name, $partner_email, $subject, $content);

            $admin_data = get_admin_user();
            $admin_email = $admin_data->email;
            $subject = sprintf(__('[%s] There is a new booking on your system - Booking ID: %s'), get_option('site_name'), $booking->ID);
            $content = view('frontend.email.' . $service_type . '.booking-detail', ['booking' => $booking, 'for' => 'admin'])->render();
            send_mail($from, $from_name, $admin_email, $subject, $content);

        }
        return false;

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

<?php
$css = '
        .text-center{
            text-align: center;
        }
        .hh-email-wrapper{
            width: 95%;
            max-width: 650px;
            margin: 20px auto;
            border: 1px solid #EEE;
            padding: 20px;
        }
        .hh-email-wrapper .header-email table{
            width: 100%;
            border: none;
            table-layout: fixed;
        }
        .hh-email-wrapper .header-email .logo{
           width: 60px;
           height: auto;
        }
        .hh-email-wrapper .header-email .description{
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0;
            margin-top: 0;
        }
        .content-email{
            padding-top: 20px;
            padding-bottom: 30px;
        }

        .booking-detail{
            margin-top: 30px;
            padding: 10px 20px;
            border: 1px solid #EEE;
        }
        .booking-detail .item{
            padding-top: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #EEE;
            display: flex;
        }
        .booking-detail .item .title{
            display: inline-block;
            font-size: 15px;
            width: 35%;
        }
        .booking-detail .item .info{
            display: inline-block;
            font-size: 15px;
            font-weight: bold;
            width: 65%;
        }
        .booking-detail .client-info .info{
            font-weight: normal;
        }
        .booking-detail .client-info .info p{
            margin-top: 0;
        }
        .button-primary{
            display: inline-block;
            padding: 10px 20px;
            border: 1px solid #f8546d;
            background: #f8546d;
            color: #FFF;
            text-align: center;
        }
        .confirmation-button{
            margin-top: 30px;
            text-transform: uppercase;
            text-decoration: none;
        }

        .footer-email{
            border-top: 1px solid #EEE;
            padding: 30px 0;
        }
        .cart-list{
            margin-top: 0;
            padding-left: 0;
        }
        .cart-list li{
            padding-top: 5px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #CCC;
        }
        .cart-list li span{
            display: inline-block;
            width: 50%;
        }
        .cart-list li span + span{
            display: inline-block;
            width: auto;
            text-align: right;
        }
        .booking-status{
            padding: 5px 11px;
            font-size: 12px;
            color: #FFF;
        }
        .booking-status.completed {
          background: #1abc9c;
        }

        .booking-status.incomplete {
          background: #f7b84b;
        }

        .booking-status.canceled {
          background: rgba(255, 80, 66, 0.78);
        }

        .booking-status.pending {
          background: #516a77;
        }

        .booking-status.refunded {
          background: #222;
        }
    ';

$service_data = get_booking_data($booking->ID, 'serviceObject');
start_get_view();
?>
<div class="hh-email-wrapper">
    <div class="header-email">
        <table>
            <tr>
                <td style="text-align: left">
                    <?php
                    $logo = get_option('email_logo');
                    $logo_url = get_attachment_url($logo);
                    ?>
                    <a href="{{ url('/') }}" target="_blank">
                        <img src="{{ $logo_url }}" alt="{{ get_option('site_description') }}" class="logo">
                    </a>
                </td>
                <td style="text-align: right">
                    <h4 class="description">{{ get_option('site_name') }}</h4>
                </td>
            </tr>
        </table>
    </div>
    <div class="content-email">
        @if($for == 'customer')
            <p>{{__('Hello')}} <strong>{{$booking->first_name}} {{$booking->last_name}}</strong>,</p>
            <p>{{__('Thank you for using our service!')}}</p>
            <p>{{__('Here is your booking information:')}}</p>
        @elseif($for == 'admin')
            <?php
            $admin_data = get_admin_user();
            ?>
            <p>{!! sprintf(__('Hello %s'), '<strong>' . $admin_data->first_name . ' ' . $admin_data->last_name . '</strong>') !!}
                ,</p>
            <p>{{__('There is a new booking on your system')}}</p>
        @elseif($for == 'partner')
            <?php
            $partner_data = get_user_by_id($booking->owner);
            ?>
            <p>{!! sprintf(__('Hello %s'), '<strong>'. $partner_data->first_name . ' ' . $partner_data->last_name .'</strong>') !!}
                ,</p>
            <p>{!! sprintf(__('%s has booked your service.'), '<strong>' . $booking->first_name . ' ' . $booking->last_name . '</strong>') !!}</p>
            <p>{{__('Here is the details of the booking:')}}</p>
        @endif
        <div class="booking-detail">
            <div class="item">
                <span class="title">{{__('Booking ID:')}}</span>
                <span class="info">{{ $booking->booking_id }}</span>
            </div>
            <div class="item">
                <span class="title">{{__('Name:')}}</span>
                <span class="info">{{ get_translate($service_data->post_title) }}</span>
            </div>
            <div class="item">
                <span class="title">{{__('Amount:')}}</span>
                <span class="info">{{ convert_price($booking->total) }}</span>
            </div>
            <div class="item">
                <span class="title">{{__('Payment Method:')}}</span>
                <span class="info">
                     <?php
                    $paymentID = $booking->payment_type;
                    $paymentObject = get_payments($paymentID);
                    ?>
                    {{ $paymentObject::getName() }}
                </span>
            </div>
            <div class="item">
                <span class="title">{{__('Status:')}}</span>
                <span class="info">
                    <?php
                    $status = $booking->status;
                    $status_info = booking_status_info($status);
                    ?>
                    <span class="booking-status {{ $status }}">{{ get_translate(__($status_info['label'])) }}</span>
                </span>
            </div>
            <div class="item">
                <span class="title">{{__('Created At:')}}</span>
                <span class="info">{{ date(hh_date_format(), $booking->created_date) }}</span>
            </div>
            <div class="item">
                <span class="title">{{__('Price Detail:')}}</span>
                <div class="info">
                    <?php
                    $cartData = get_booking_data($booking->ID, 'cartData');
                    $checkout_data = get_booking_data($booking->ID);
                    ?>
                    <ul class="cart-list">
                        <?php
                        $checkIn = $cartData['startTime'];
                        $checkOut = $cartData['endTime'];
                        $adults = $cartData['numberAdult'];
                        $children = $cartData['numberChild'];
                        $infant = $cartData['numberInfant'];
                        ?>
                        @if($service_data->booking_type == 'per_night')
                            <li>
                                <span>{{__('Check In/Out')}}</span>
                                <span>
                                {{ date(hh_date_format(), $checkIn) }} - {{ date(hh_date_format(), $checkOut) }}
                            </span>
                            </li>
                        @else
                            <li>
                                <span>{{__('Date')}}</span>
                                <span>
                                {{ date(hh_date_format(), $checkIn) }}
                                </span>
                            </li>
                            <li>
                                <span>{{__('Time')}}</span>
                                <span>
                                {{ date(hh_time_format(), $checkIn) }} - {{ date(hh_time_format(), $checkOut) }}
                            </span>
                            </li>
                        @endif
                        @if ($adults > 0)
                            <li>
                                <span>{{ _n("[0::Adults][1::Adult][2::Adults]", $adults) }}</span>
                                <span> {{ $adults }}  </span>
                            </li>
                        @endif
                        @if ($children > 0)
                            <li>
                                <span>{{ _n("[0::Children][1::Child][2::Children]", $children) }}</span>
                                <span>{{ $children }}</span>
                            </li>
                        @endif
                        @if ($infant > 0)
                            <li>
                                <span>{{ _n("[0::Infants][1::Infant][2::Infants]", $infant) }}</span>
                                <span>{{ $infant }}</span>
                            </li>
                        @endif
                    </ul>
                    <ul class="menu cart-list">
                        <?php
                        $numberNight = $cartData['numberNight'];
                        $basePrice = $checkout_data['basePrice'];
                        $extraPrice = $checkout_data['extraPrice'];
                        $tax = $checkout_data['tax'];
                        $coupon = isset($checkout_data['couponPrice']) ? $checkout_data['couponPrice'] : '';
                        ?>
                        <li>
                            <span>{{ _n("[0::Price for %s nights][1::Price for %s night][2::Price for %s nights]", $numberNight) }}</span>
                            <span>{{ convert_price($basePrice) }}</span>
                        </li>
                        @if ($extraPrice > 0)
                            <li>
                                <span>{{__('Extra Service')}}</span>
                                <span>{{ convert_price($extraPrice) }}</span>
                            </li>
                        @endif
                        @if (!empty($coupon))
                            <li>
                                <span>{{__('Coupon')}}</span>
                                <span>-{{ $coupon }}</span>
                            </li>
                        @endif
                        <?php
                        $tax_value = (float)$checkout_data['tax']['tax'];
                        if($tax_value > 0):
                        ?>
                        <li class="divider">
                            <span>{{__('Tax') }}
                                <span class="text-muted">
                                    @if ($checkout_data['tax']['included'] == 'on')
                                        {{__('(included)')}}
                                    @endif
                                </span>
                            </span>
                            <span>{{ $tax_value }}%</span>
                        </li>
                        <?php endif; ?>
                        <li class="amount">
                            <span>{{__('Amount')}}</span>
                            <span>{{ convert_price($checkout_data['amount']) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            @if($for == 'customer')
                <div class="item client-info">
                    <span class="title">{{__('Your information:')}}</span>
                    <?php
                    $user_data = get_booking_data($booking->ID, 'user_data');
                    ?>
                    <span class="info">
                    <p>{{ $user_data['firstName'] }} {{ $user_data['lastName'] }}</p>
                    <p>{{ $user_data['email'] }}</p>
                    <p>{{ $user_data['phone'] }}</p>
                    <p>{{ $user_data['address'] }} | {{ $user_data['city'] }} | {{ $user_data['postCode'] }}</p>
                    @if($booking->note)
                            <p>{{__('Note:')}} {{ $booking->note }}</p>
                        @endif
                </span>
                </div>
            @endif
        </div>
        <div class="text-center">
            <a class="button-primary confirmation-button"
               href="{{ dashboard_url('all-booking') }}">{{__('Your Bookings')}}</a>
        </div>
    </div>
    <div class="footer-email">
        &copy; {{ date('Y') }} - {{ get_option('site_name') }} | {{ get_option('site_description') }}
    </div>
</div>
<?php
$content = end_get_view();
$render = new Emogrifier();
$render->setHtml($content);
$render->setCss($css);
$mergedHtml = $render->emogrify();
$mergedHtml = str_replace('<!DOCTYPE html>', '', $mergedHtml);
unset($render);
?>
{!! balanceTags($mergedHtml) !!}

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
                        $checkIn = $cartData['startDateTime'];
                        $checkOut = $cartData['endDateTime'];
                        $number = $cartData['number'];
                        $booking_type = isset($cartData['bookingType']) ? $cartData['bookingType'] : 'day';
                        ?>
                        @if ($number > 0)
                            <li>
                                <span>{{ __('Number') }}</span>
                                <span> {{ $number }}  </span>
                            </li>
                        @endif
                        <li>
                            <span>{{__('From')}}</span>
                            <span>
                                {{ date(hh_date_format(true), $checkIn) }}
                            </span>
                        </li>
                        <li>
                            <span>{{__('To')}}</span>
                            <span>
                                {{ date(hh_date_format(true), $checkOut) }}
                            </span>
                        </li>
                        <?php
                        $number_hour = isset($cartData['numberHour']) ? $cartData['numberHour'] : 0;
                        $number_day = isset($cartData['numberDay']) ? $cartData['numberDay'] : 0;
                        ?>
                        @if(($booking_type == 'hour' && $number_hour > 0) || ($booking_type == 'day' && $number_day > 0))
                            <li>
                                <span>{{__('Duration')}}</span>
                                <span>
                                @if($booking_type == 'hour')
                                        {{_n("[0::%s hours][1::%s hour][2::%s hours]",  $number_hour)}}
                                    @elseif($booking_type == 'day')
                                        {{_n("[0::%s days][1::%s day][2::%s days]",  $number_day)}}
                                    @endif
                            </span>
                            </li>
                        @endif
                        @if(!empty($cartData['equipmentData']))
                            <li>
                                <span style="vertical-align: middle">{{__('Equipments')}}</span>
                                <span style="vertical-align: middle">
                                @foreach($cartData['equipmentData'] as $eq)
                                        <p style="text-align: left">
                                        {{get_translate($eq->term_title)}}
                                    </p>
                                    @endforeach
                            </span>
                            </li>
                        @endif

                        @if(isset($cartData['insuranceData']) && !empty($cartData['insuranceData']))
                            <li>
                                <span style="vertical-align: middle">{{__('Insurance Plan')}}</span>
                                <span style="vertical-align: middle">
                                @foreach($cartData['insuranceData'] as $ip)
                                        <p style="text-align: left">
                                        {{get_translate($ip['name'])}}
                                    </p>
                                    @endforeach
                            </span>
                            </li>
                        @endif
                    </ul>
                    <ul class="menu cart-list">
                        <?php
                        $basePrice = $checkout_data['basePrice'];
                        $subTotalPrice = $checkout_data['subTotal'];
                        $equipmentPrice = isset($checkout_data['equipmentPrice']) ? $checkout_data['equipmentPrice'] : 0;
                        $insurancePrice = isset($checkout_data['insurancePrice']) ? $checkout_data['insurancePrice'] : 0;
                        $tax = $checkout_data['tax'];
                        $coupon = isset($checkout_data['couponPrice']) ? $checkout_data['couponPrice'] : '';
                        ?>
                        <li>
                            <span>{{ __('Price Car Rental') }}</span>
                            <span>{{ convert_price($basePrice) }}</span>
                        </li>
                        @if ($equipmentPrice > 0)
                            <li>
                                <span>{{__('Equipment Price')}}</span>
                                <span>{{ convert_price($equipmentPrice) }}</span>
                            </li>
                        @endif

                        @if ($insurancePrice > 0)
                            <li>
                                <span>{{__('Insurance Price')}}</span>
                                <span>{{ convert_price($insurancePrice) }}</span>
                            </li>
                        @endif

                        @if (!empty($coupon))
                            <li>
                                <span>{{__('Coupon')}}</span>
                                <span>-{{ $coupon }}</span>
                            </li>
                        @endif
                        <li>
                            <span>{{ __('Sub total') }}</span>
                            <span>{{ convert_price($subTotalPrice) }}</span>
                        </li>
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

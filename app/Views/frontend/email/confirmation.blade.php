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
            padding: 10px 15px;
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
            font-weight: bold
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
        <p>{{__('Hello')}} <strong>{{$booking->first_name}} {{$booking->last_name }}</strong>,</p>
        <p>{{__('You have booked a service on our system. ')}}</p>
        <p>{{__('Please read the information below and click Confirm to let the system confirm the request.')}}</p>
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
                <span class="title">{{__('Created At:')}}</span>
                <span class="info">{{ date(hh_date_format(), $booking->created_date) }}</span>
            </div>
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
        </div>
        <?php
        $encrypt = create_confirmation_code($booking);
        ?>
        <div class="text-center">
            <a class="button-primary confirmation-button"
               href="{{ dashboard_url('booking-confirmation?token='.$booking->token_code.'&code='. $encrypt) }}">{{__('Confirm')}}</a>
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

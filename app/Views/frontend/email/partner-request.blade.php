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
        .booking-detail .item.last{
            padding-bottom: 10px;
            border: none;
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

start_get_view();
?>
    <!doctype html>
<html lang="en">
<head>
    <title>{{__('A new registration partner needs approval')}}</title>
</head>
<body>
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
        <h2 style="color:#0079c1; margin-bottom: 20px;">{{__('A new registration partner needs approval')}}</h2>
        <p>{{__('Partner registration information')}}</p>
        <div class="booking-detail">
            <div class="item">
                <span class="title">{{__('User ID:')}}</span>
                <span class="info">{{ $user->getUserId() }}</span>
            </div>
            <div class="item">
                <span class="title">{{__('Email:')}}</span>
                <span class="info">{{ $user->email }}</span>
            </div>
            <div class="item">
                <span class="title">{{__('Gender:')}}</span>
                <span class="info">{{ get_gender_name(get_user_meta($user->getUserId(), 'gender')) }}</span>
            </div>
            <div class="item">
                <span class="title">{{__('First Name:')}}</span>
                <span class="info">{{ $user->first_name }}</span>
            </div>
            <div class="item">
                <span class="title">{{__('Last Name:')}}</span>
                <span class="info">{{ $user->last_name }}</span>
            </div>
            <div class="item">
                <span class="title">{{__('Mobile:')}}</span>
                <span class="info">{{ $user->mobile }}</span>
            </div>
            <div class="item">
                <span class="title">{{__('Address:')}}</span>
                <span class="info">{{ $user->address }}</span>
            </div>
            <?php
            $city = get_user_meta($user->getUserId(), 'city');
            $zipcode = get_user_meta($user->getUserId(), 'zipcode');
            $location = $user->location;
            ?>
            @if($city)
                <div class="item">
                    <span class="title">{{__('City:')}}</span>
                    <span class="info">{{ $city }}</span>
                </div>
            @endif
            @if($location)
                <div class="item">
                    <span class="title">{{__('Country:')}}</span>
                    <span class="info">{{ get_country_by_code($location)['name'] }}</span>
                </div>
            @endif
            @if($zipcode)
                <div class="item">
                    <span class="title">{{__('Zipcode:')}}</span>
                    <span class="info">{{ $zipcode }}</span>
                </div>
            @endif
        </div>
        <br/>
        <p style="margin-bottom: 0;">{{__('Yours sincerely,')}}</p>
        <p style="margin-top: 5px;">{{get_option('site_name')}}</p>
    </div>
    <div class="footer-email">
        &copy; {{ date('Y') }} - {{ get_option('site_name') }} | {{ get_option('site_description') }}
    </div>
</div>
</body>
</html>
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

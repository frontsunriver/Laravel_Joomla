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

$date_payout = $payout_item->created;
?>
    <!doctype html>
<html lang="en">
<head>
    <title>{{sprintf(__('We have confirmed and paid your payout. in %s.'), date('M Y', $date_payout))}}</title>
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
        <h2 style="color:#0079c1; margin-bottom: 20px;">{{__('Your payout has been paid.')}}</h2>
        <p>{{__('Hello')}} <strong>{{get_username($userdata->getUserId())}}</strong>,</p>
        <p>{{sprintf(__('We have confirmed and paid your payout in %s.'), date('M Y', $date_payout))}}</p>
        <br/>
        <h2 style="text-align: center; color: #2a2a2a;">{{ convert_price($payout_item->amount) }}</h2>

        <br/>
        <p>{{__('If something goes wrong, it may take up to a week for your bank to let us know. We will email you immediately if we discover any problems.')}}</p>
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

<?php
if (empty($bookingObject)) {
    return;
}
$status = booking_status_info($bookingObject->status);
$bookingID = $bookingObject->ID;
$paymentID = $bookingObject->payment_type;
$paymentObject = get_payments($paymentID);
$serviceObject = get_booking_data($bookingID, 'serviceObject');
$bookingData = get_booking_data($bookingID);
?>
<ul class="nav nav-tabs nav-bordered">
    <li class="nav-item">
        <a href="#tab-invoice-payment-info" data-toggle="tab" aria-expanded="false" class="nav-link active">
            {{__('Transaction')}}
        </a>
    </li>
    <li class="nav-item">
        <a href="#tab-invoice-customer" data-toggle="tab" aria-expanded="true" class="nav-link">
            {{__('Customer')}}
        </a>
    </li>
    @if(is_admin())
        <li class="nav-item">
            <a href="#tab-invoice-partner" data-toggle="tab" aria-expanded="true" class="nav-link">
                {{__('Owner')}}
            </a>
        </li>
    @endif
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="tab-invoice-payment-info">
        <div class="payment-item">
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Booking ID')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{ $bookingObject->booking_id }}</span>
                </div>
            </div>
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Service Name')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{ get_translate($serviceObject->post_title) }}</span>
                </div>
            </div>
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('No. Guest')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{ $bookingData['cartData']['numberGuest'] }}</span>
                </div>
            </div>
            @if($serviceObject->booking_type == 'per_night')
                <div class="item row align-items-center">
                    <div class="col-4">
                        <h5 class="title">{{__('Check In/Out')}}</h5>
                    </div>
                    <div class="col-8">
                    <span>{{ date(hh_date_format(), $bookingObject->start_date) }}
                        <i class="fe-arrow-right ml-2 mr-2"></i>
                        {{ date(hh_date_format(), $bookingObject->end_date) }}
                    </span>
                    </div>
                </div>
            @elseif($serviceObject->booking_type == 'per_hour')
                <div class="item row align-items-center">
                    <div class="col-4">
                        <h5 class="title">{{__('Date')}}</h5>
                    </div>
                    <div class="col-8">
                        <span>{{ date(hh_date_format(), $bookingObject->start_date) }}</span>
                    </div>
                </div>
                <div class="item row align-items-center">
                    <div class="col-4">
                        <h5 class="title">{{__('Time')}}</h5>
                    </div>
                    <div class="col-8">
                        <span>{{ date(hh_time_format(), $bookingObject->start_time) }}
                            <i class="fe-arrow-right ml-2 mr-2"></i>
                            {{ date(hh_time_format(), $bookingObject->end_time) }}
                    </span>
                    </div>
                </div>
            @endif
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Status')}}</h5>
                </div>
                <div class="col-8">
                    <span class="booking-status {{ $bookingObject->status }}">{{ __($status['label']) }}</span>
                </div>
            </div>
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Payment Method')}}</h5>
                </div>
                <div class="col-8">
                    @if(!empty($paymentObject))
                        <span>{{ $paymentObject::getName() }}</span>
                    @else
                        <span>{{ __('Not Available') }}</span>
                    @endif
                </div>
            </div>
            <div class="divider"></div>
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Base Price')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{ convert_price($bookingData['basePrice']) }}</span>
                </div>
            </div>
            <?php
            $extraPrice = $bookingData['extraPrice'];
            ?>
            @if ($extraPrice > 0)
                <div class="item row align-items-center">
                    <div class="col-4">
                        <h5 class="title">{{__('Extra')}}</h5>
                    </div>
                    <div class="col-8">
                        <span>{{ convert_price($extraPrice) }}</span>
                    </div>
                </div>
            @endif
            <?php
            $coupon = isset($bookingData['cartData']['coupon']) ? $bookingData['cartData']['coupon'] : [];
            ?>
            @if ($coupon)
                <div class="item row align-items-center">
                    <div class="col-4">
                        <h5 class="title">{{__('Coupon')}}</h5>
                    </div>
                    <div class="col-8">
                        <span>- {{ $coupon->couponPriceHtml }} ({{ $coupon->coupon_code }})</span>
                    </div>
                </div>
            @endif
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Tax')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{ $bookingData['tax']['tax'] }}%</span>
                </div>
            </div>
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Amount')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{ convert_price($bookingObject->total) }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="tab-invoice-customer">
        <div class="payment-item">
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('First Name')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{ $bookingObject->first_name }}</span>
                </div>
            </div>
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Last Name')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{ $bookingObject->last_name }}</span>
                </div>
            </div>
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Email')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{ $bookingObject->email }}</span>
                </div>
            </div>
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Phone')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{ $bookingObject->phone }}</span>
                </div>
            </div>
            <div class="item row align-items-center">
                <div class="col-4">
                    <h5 class="title">{{__('Address')}}</h5>
                </div>
                <div class="col-8">
                    <span>{{ $bookingObject->address }}</span>
                </div>
            </div>
            @if(!empty($bookingObject->note))
                <div class="item row align-items-center">
                    <div class="col-4">
                        <h5 class="title">{{__('Note')}}</h5>
                    </div>
                    <div class="col-8">
                        <span>{{ $bookingObject->note }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if(is_admin())
        <?php
        $partner_id = $bookingObject->owner;
        $partner_data = get_user_by_id($partner_id);
        ?>
        <div class="tab-pane" id="tab-invoice-partner">
            <div class="payment-item">
                <div class="item row align-items-center">
                    <div class="col-4">
                        <h5 class="title">{{__('Full Name')}}</h5>
                    </div>
                    <div class="col-8">
                        <span>{{ get_username($partner_id) }}</span>
                    </div>
                </div>
                <div class="item row align-items-center">
                    <div class="col-4">
                        <h5 class="title">{{__('Email')}}</h5>
                    </div>
                    <div class="col-8">
                        <span>{{ $partner_data->email }}</span>
                    </div>
                </div>
                <div class="item row align-items-center">
                    <div class="col-4">
                        <h5 class="title">{{__('Phone')}}</h5>
                    </div>
                    <div class="col-8">
                        <span>{{ $partner_data->mobile }}</span>
                    </div>
                </div>
                <div class="item row align-items-center">
                    <div class="col-4">
                        <h5 class="title">{{__('Address')}}</h5>
                    </div>
                    <div class="col-8">
                        <span>{{ $partner_data->address }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

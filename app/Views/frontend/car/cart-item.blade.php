<?php
if (!isset($cart)) {
    return;
}

$serviceID = $cart['serviceID'];
$serviceObject = unserialize($cart['serviceObject']);
?>
<h3 class="heading">{{__('Your Item')}}</h3>
<div class="card-box mt-3 cart-information cart-experience-item">
    <div class="media service-detail d-flex align-items-start">
        <?php
        $thumbnail = get_attachment_url($serviceObject->thumbnail_id, [400, 400])
        ?>
        <img src="{{ $thumbnail }}" class="mr-3"
             alt="{{ get_attachment_alt($serviceObject->thumbnail_id) }}">
        <div class="media-body">
            <a target="_blank"
               href="{{ get_car_permalink($serviceID, $serviceObject->post_slug) }}">{{ get_translate($serviceObject->post_title) }}</a>
            @if ($address = get_translate($serviceObject->location_address))
                <div class="desc mt-2">
                    <i class="fe-map-pin mr-1"></i> {{ $address }}
                </div>
            @endif
        </div>
    </div>
    <h5 class="title">{{__('Detail')}}</h5>
    <ul class="menu cart-list">
        <?php
        $startDateTime = $cart['cartData']['startDateTime'];
        $endDateTime = $cart['cartData']['endDateTime'];
        $number = $cart['cartData']['number'];
        ?>
        <li>
            <span>{{__('From')}}</span>
            <span>
                {{ date(hh_date_format(true), $startDateTime) }}
            </span>
        </li>
        <li>
            <span>{{__('To')}}</span>
            <span>
                {{ date(hh_date_format(true), $endDateTime) }}
            </span>
        </li>
        <li>
            <span>{{__('Number')}}</span>
            <span>
                {{$number}}
            </span>
        </li>
    </ul>
    <?php
    $coupon = isset($cart['cartData']['coupon']) ? $cart['cartData']['coupon'] : [];
    $couponCode = isset($coupon->coupon_code) ? $coupon->coupon_code : '';
    ?>
    <form action="{{ url('add-coupon') }}" class="form-sm form-action form-add-coupon"
          data-validation-id="form-add-coupon"
          method="post"
          data-reload-time="1000">
        @include('common.loading')
        <div class="form-group">
            <label for="coupon">{{__('Coupon Code')}}</label>
            <input id="coupon" type="text" class="form-control" name="coupon"
                   value="{{ $couponCode }}"
                   placeholder="{{__('Have a coupon?')}}">
            <input type="hidden" name="service_id"
                   value="{{ $serviceID }}">
            <input type="hidden" name="service_type"
                   value="car">
            <button class="btn" type="submit" name="sm"><i class="fe-arrow-right "></i>
            </button>
        </div>
        <div class="form-message"></div>
    </form>
    <h5 class="title">{{__('Summary')}}</h5>
    <ul class="menu cart-list">
        <?php
        $equipment_price = $cart['equipmentPrice'];
        $insurance_price = $cart['insurancePrice'];
        $tax = $cart['tax'];
        ?>
        <li>
            <span>{{__('Car Rental Price')}}</span>
            <span>{{ convert_price($cart['basePrice']) }}</span>
        </li>
        @if ($equipment_price > 0)
            <li>
                <span>{{__('Equipment Price')}}</span>
                <span>{{ convert_price($equipment_price) }}</span>
            </li>
        @endif
        @if ($insurance_price > 0)
            <li>
                <span>{{__('Insurance Price')}}</span>
                <span>{{ convert_price($insurance_price) }}</span>
            </li>
        @endif
        @if (!empty($coupon))
            <li>
                <form action="{{ url('remove-coupon') }}" class="form-action" method="post"
                      data-validation-id="form-remove-coupon"
                      data-reload-time="1500">
                    @include('common.loading')
                    <input type="hidden" name="carID"
                           value="{{ $serviceID }}">
                    <div class="d-flex align-items-center">
                        <span>
                            {{__('Coupon')}}
                            <button class="btn ml-2" type="submit" name="sm">{{__('(remove)')}}</button>
                        </span>
                        <span>- {{ $coupon->couponPriceHtml }}</span>
                    </div>
                    <div class="form-message"></div>
                </form>
            </li>
        @endif
        <li>
            <span>{{__('Sub Total')}}</span>
            <span>{{ convert_price($cart['subTotal']) }}</span>
        </li>
        <?php
        $tax_value = (float)$cart['tax']['tax'];
        if($tax_value > 0){
        ?>
        <li class="divider pt-2">
            <span>{{__('Tax')}}
                <span class="text-muted">
                    @if ($cart['tax']['included'] == 'on')
                        {{__('(included)')}}
                    @endif
                </span>
            </span>
            <span>{{ $tax_value }}%</span>
        </li>
        <?php } ?>
        <li class="amount">
            <span>{{__('Amount')}}</span>
            <span>{{ convert_price($cart['amount']) }}</span>
        </li>
    </ul>
</div>

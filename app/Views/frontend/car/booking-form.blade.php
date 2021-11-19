<?php
global $post;
?>
<form class="form-action" action="{{ url('add-to-cart-car') }}" method="post"
      data-validation-id="form-add-to-cart"
      data-loading-from=".form-body">

    <div class="form-group">
        <label for="checkinout">{{ __('Date') }}</label>
        <div class="date-wrapper date-double"
             data-date-format="{{ hh_date_format_moment() }}"
             data-action="{{ url('get-car-availability-single') }}"
             data-action-time="{{ url('get-car-availability-time-single') }}"
             data-single-click="false"
             data-same-date="true">
            <input type="text" class="input-hidden check-in-out-field"
                   name="checkInOut" data-car-id="{{ $post->post_id }}"
                   data-car-encrypt="{{ hh_encrypt($post->post_id) }}" value="{{$checkInOut}}">
            <input type="text" class="input-hidden check-in-field"
                   name="checkIn" value="{{$checkIn}}">
            <input type="text" class="input-hidden check-out-field"
                   name="checkOut" value="{{$checkOut}}">
            <span class="check-in-render"
                  data-date-format="{{ hh_date_format_moment() }}"></span>
            <span class="divider"></span>
            <span class="check-out-render"
                  data-date-format="{{ hh_date_format_moment() }}"></span>
        </div>
    </div>

    <div
        class="form-group form-group-date-time @if($booking_type == 'hour' && (empty($checkInTime) || empty($checkOutTime))) d-none @endif">
        <label>{{ __('Time') }}</label>
        <?php
        if ($booking_type == 'day' || (!empty($checkInTime) && !empty($checkOutTime))) {
            $listTime = list_hours(15);
        }
        ?>
        <div class="date-wrapper date-time">
            <div class="date-render check-in-render"
                 data-time-format="{{ hh_time_format() }}"
                 data-same-time="0">
                <div class="render">
                    @if(!empty($checkInTime))
                        {{$checkInTime}}
                    @else
                        {{__('Start Time')}}
                    @endif
                </div>
                <div class="dropdown-time">
                    @if($booking_type == 'day' || (!empty($checkInTime) && !empty($checkOutTime)))
                        @foreach($listTime as $key => $value)
                            <div class="item @if($checkInTime == $key) active @endif"
                                 data-value="{{ $key }}">{{ $value }}</div>
                        @endforeach
                    @endif
                </div>
                <input type="hidden" name="startTime" value="{{$checkInTime}}"
                       class="input-checkin"/>
            </div>
            <span class="divider"></span>
            <div class="date-render check-out-render"
                 data-time-format="{{ hh_time_format() }}">
                <div class="render">
                    @if(!empty($checkOutTime))
                        {{$checkOutTime}}
                    @else
                        {{__('End Time')}}
                    @endif
                </div>
                <div class="dropdown-time">
                    @if($booking_type == 'day' || (!empty($checkInTime) && !empty($checkOutTime)))
                        @foreach($listTime as $key => $value)
                            <div class="item @if($checkOutTime == $key) active @endif"
                                 data-value="{{ $key }}">{{ $value }}</div>
                        @endforeach
                    @endif
                </div>
                <input type="hidden" name="endTime" value="{{$checkOutTime}}"
                       class="input-checkin"/>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?php
        if (!empty($post->quantity)) {
            $quantity = $post->quantity;
        } else {
            $quantity = 20;
        }
        ?>
        <div class="guest-group">
            <div class="d-flex align-items-center justify-content-between">
                <span class="pull-left">{{__('Number')}}</span>
                <div class="d-flex align-items-center">
                    <i class="decrease ti-minus"></i>
                    <input type="text" min="1" step="1" max="{{$quantity}}" name="number" value="1" readonly=""
                           class="car-number">
                    <i class="increase ti-plus"></i>
                </div>
            </div>
        </div>
    </div>

    <?php
    $equipments = get_equipments($post->equipments, $post->tax_car_equipments);
    ?>
    @if(count($equipments) > 0)
        <div class="fomr-group">
            <div class="extra-services">
                <label class="d-block mb-2" for="extra-services">
                    <span>{{__('Equipments')}}</span>
                    <a href="#extra-not-required-collapse" class="right"
                       data-toggle="collapse">{!! get_icon('002_download_1', '#2a2a2a','15px') !!}</a>
                </label>

                <div id="extra-not-required-collapse" class="collapse">
                    @foreach ($equipments as $key => $val)
                        <div class="extra-item d-flex">
                            <div class="checkbox checkbox-success">
                                <input
                                    id="extra-service-{{ $key }}"
                                    class="input-extra"
                                    type="checkbox" name="equipment[]"
                                    value="{{ $key }}">
                                <label
                                    for="extra-service-{{ $key }}">
                                    <span
                                        class="name">{{ get_translate($val['title']) }}</span>
                                </label>
                            </div>
                            <span
                                class="price ml-auto">{{ convert_price($val['price']) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <?php
    $insurance_plan = $post->insurance_plan;
    $insurance_plan = maybe_unserialize($insurance_plan);
    ?>
    @if(!empty($insurance_plan))
        <div class="extra-services">
            <label class="d-block mb-2" for="extra-services">
                <span>{{__('Insurance Plan')}}</span>
                <a href="#extra-not-required-collapse1" class="right"
                   data-toggle="collapse">{!! get_icon('002_download_1', '#2a2a2a','15px') !!}</a>
            </label>
            <div id="extra-not-required-collapse1" class="collapse">
                @foreach ($insurance_plan as $ip)
                    <?php
                    if ($ip['fixed'] == 'on') {
                        $price_type = __('Fixed Price');
                    } else {
                        $price_type = sprintf(__('Price per %s'), $booking_type);
                    }
                    ?>
                    <div class="extra-item d-flex">
                        <div class="checkbox checkbox-success">
                            <input
                                id="extra-service-{{ $ip['name_unique'] }}"
                                class="input-extra"
                                type="checkbox" name="insurancePlan[]"
                                value="{{ $ip['name_unique'] }}">
                            <label
                                for="extra-service-{{ $ip['name_unique'] }}">
                                    <span
                                        class="name">{{ get_translate($ip['name']) }}</span> <i
                                    class="position-relative t-2 c-666 dripicons-information field-desc"
                                    data-toggle="tooltip" data-placement="top" data-html="true"
                                    title="{{$ip['description']}}<h4>{{$price_type}}</h4>"></i>
                            </label>
                        </div>
                        <span
                            class="price ml-auto">{{ convert_price($ip['price']) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="form-group form-render">
    </div>
    <div class="form-group mt-2 mb-0">
        <input type="hidden" name="carID" value="{{ $post->post_id }}">
        <input type="hidden" name="carEncrypt"
               value="{{ hh_encrypt($post->post_id) }}">
        <input type="submit" class="btn btn-primary btn-block text-uppercase"
               name="sm"
               value="{{__('Book Now')}}">
    </div>
    <div class="form-message"></div>
</form>

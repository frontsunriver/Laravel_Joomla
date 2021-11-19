<?php
$booking_type = get_car_booking_type();
?>
<h1 class="h3">{{ __('Search for Cheap Rental Cars') }}</h1>
<form action="{{ get_car_search_page() }}" class="form mt-3" method="get">
    <div class="form-group">
        <label>{{__('Where')}}</label>
        <div class="form-control" data-plugin="mapbox-geocoder" data-value=""
             data-current-location="1"
             data-your-location="{{__('Your Location')}}"
             data-placeholder="{{__('Enter a location ...')}}" data-lang="{{get_current_language()}}"></div>
        <div class="map d-none"></div>
        <input type="hidden" name="lat" value="">
        <input type="hidden" name="lng" value="">
        <input type="hidden" name="address" value="">
    </div>
    <div class="form-group form-group-date">
        <label>{{__('Date')}}</label>
        <div class="date-wrapper date date-double"
             data-date-format="{{ hh_date_format_moment() }}"
             data-single-click="false"
             data-same-date="true">
            <input type="text" class="input-hidden check-in-out-field" name="checkInOut">
            <input type="text" class="input-hidden check-in-field" name="checkIn">
            <input type="text" class="input-hidden check-out-field" name="checkOut">
            <span class="check-in-render"
                  data-date-format="{{ hh_date_format_moment()  }}"></span>
            <span class="divider"></span>
            <span class="check-out-render"
                  data-date-format="{{ hh_date_format_moment()  }}"></span>
        </div>
    </div>

    @if($booking_type == 'hour')
        <div class="form-group form-group-date-time">
            <label>{{ __('Time') }}</label>
            <?php
            $listTime = list_hours(15);
            ?>
            <div class="date-wrapper date-time">
                <div class="date-render check-in-render"
                     data-time-format="{{ hh_time_format() }}"
                     data-same-time="1">
                    <div class="render">{{__('Start Time')}}</div>
                    <div class="dropdown-time">
                        @foreach($listTime as $key => $value)
                            <div class="item" data-value="{{ $key }}">{{ $value }}</div>
                        @endforeach
                    </div>
                    <input type="hidden" name="checkInTime" value=""
                           class="input-checkin"/>
                </div>
                <span class="divider"></span>
                <div class="date-render check-out-render"
                     data-time-format="{{ hh_time_format() }}">
                    <div class="render">{{__('End Time')}}</div>
                    <div class="dropdown-time">
                        @foreach($listTime as $key => $value)
                            <div class="item" data-value="{{ $key }}">{{ $value }}</div>
                        @endforeach
                    </div>
                    <input type="hidden" name="checkOutTime" value=""
                           class="input-checkin"/>
                </div>
            </div>
        </div>
    @endif

    <?php
    $minmax = get_car_min_max_price();
    $currencySymbol = current_currency('symbol');
    $currencyPos = current_currency('position');
    ?>
    <div class="form-group">
        <label>{{__('Price Range')}}</label>
        <input type="text" name="price_filter"
               data-plugin="ion-range-slider"
               data-prefix="{{ $currencyPos == 'left' ? $currencySymbol : ''}}"
               data-postfix="{{ $currencyPos == 'right' ? $currencySymbol : ''}}"
               data-min="{{ $minmax['min'] }}"
               data-max="{{ $minmax['max'] }}"
               data-from="{{ $minmax['min'] }}"
               data-to="{{ $minmax['max'] }}"
               data-skin="round">
    </div>
    <div class="form-group">
        <input class="btn btn-primary w-100" type="submit" name="sm"
               value="{{__('Search')}}">
    </div>
</form>

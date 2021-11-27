<h1 class="h3">{{ __('Book unique places to stay') }}</h1>
<form action="{{ get_home_search_page() }}" class="form mt-3" method="get">
    <div class="form-group">
        <label>{{__('Where')}}</label>
        <!-- <div class="form-control" data-plugin="mapbox-geocoder" data-value=""
             data-current-location="1"
             data-your-location="{{__('Your Location')}}"
             data-placeholder="{{__('Enter a location ...')}}" data-lang="{{get_current_language()}}"></div> -->
             
        <input type="text" id="demo4" name="address" class="form-control typeahead" autocomplete="off" placeholder="{{__('Enter a location ...')}}">
        <div class="map d-none"></div>
        <input type="hidden" name="lat" value="">
        <input type="hidden" name="lng" value="">
    </div>
    <!-- <div class="form-group">
        <div class="radio radio-pink form-check-inline ml-1">
            <input id="booking_type_home_night" type="radio" name="bookingType" value="per_night"
                   checked>
            <label for="booking_type_home_night">{{ __('per Night') }}</label>
        </div>
        <div class="radio radio-pink form-check-inline ml-1">
            <input id="booking_type_home_hour" type="radio" name="bookingType" value="per_hour">
            <label for="booking_type_home_hour">{{ __('per Hour') }}</label>
        </div>
    </div> -->

    <div class="form-group form-group-date-single d-none">
        <label>{{__('Check In')}}</label>
        <div class="date-wrapper date date-single"
             data-date-format="d.m.Y">
            <input type="text"
                   class="input-hidden check-in-out-field"
                   name="checkInOutTime">
            <input type="text" class="input-hidden check-in-field"
                   name="checkInTime">
            <input type="text" class="input-hidden check-out-field"
                   name="checkOutTime">
            <span class="check-in-render"
                  data-date-format="d.m.Y"></span>
        </div>
    </div>
    <div class="form-group form-group-date-time d-none">
        <label>{{ __('Time') }}</label>
        <?php
        $listTime = list_hours(30);
        ?>
        <div class="date-wrapper date-time">
            <div class="date-render check-in-render"
                 data-time-format="{{ hh_time_format() }}">
                <div class="render">{{__('Start Time')}}</div>
                <div class="dropdown-time">
                    @foreach($listTime as $key => $value)
                        <div class="item" data-value="{{ $key }}">{{ $value }}</div>
                    @endforeach
                </div>
                <input type="hidden" name="startTime" value=""
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
                <input type="hidden" name="endTime" value=""
                       class="input-checkin"/>
            </div>
        </div>
    </div>
    <div class="form-group form-group-date">
        <label>{{__('Check In/Out')}}</label>
        <div class="date-wrapper date date-double" data-date-format="{{ hh_date_format_moment()  }}">
            <input type="text" class="input-hidden check-in-out-field" name="checkInOut">
            <input type="text" class="input-hidden check-in-field" name="checkIn">
            <input type="text" class="input-hidden check-out-field" name="checkOut">
            <span class="check-in-render"
                  data-date-format="DD.MM.YYYY."></span>
            <span class="divider"></span>
            <span class="check-out-render"
                  data-date-format="DD.MM.YYYY."></span>
        </div>
    </div>
    <div class="form-group">
        <label>{{__('Guests')}}</label>
        <div class="guest-group">
            <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown"
                    data-text-guest="{{__('Guest')}}"
                    data-text-guests="{{__('Guests')}}"
                    data-text-infant="{{__('Infant')}}"
                    data-text-infants="{{__('Infants')}}"
                    aria-haspopup="true" aria-expanded="false">
                &nbsp;
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="group">
                    <span class="pull-left">{{__('Adults')}}</span>
                    <div class="control-item">
                        <i class="decrease ti-minus"></i>
                        <input type="number" min="1" step="1" max="15" name="num_adults" value="1">
                        <i class="increase ti-plus"></i>
                    </div>
                </div>
                <div class="group">
                    <span class="pull-left">{{__('Children')}}</span>
                    <div class="control-item">
                        <i class="decrease ti-minus"></i>
                        <input type="number" min="0" step="1" max="15" name="num_children"
                               value="0">
                        <i class="increase ti-plus"></i>
                    </div>
                </div>
                <div class="group">
                    <span class="pull-left">{{__('Infants')}}</span>
                    <div class="control-item">
                        <i class="decrease ti-minus"></i>
                        <input type="number" min="0" step="1" max="10" name="num_infants" value="0">
                        <i class="increase ti-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $minmax = \App\Controllers\Services\HomeController::get_inst()->getMinMaxPrice();
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
    <div class="form-group">
        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal">{{__('Advanced Search')}}</a>
    </div>
</form>

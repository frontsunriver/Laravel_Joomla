<h1 class="h3">{{ __('One-of-a-kind experiences') }}</h1>
<form action="{{ get_experience_search_page() }}" class="form mt-3" method="get">
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
        <label>{{__('Dates')}}</label>
        <div class="date-wrapper date date-double" data-date-format="{{ hh_date_format_moment()  }}">
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
    <div class="form-group">
        <label>{{__('Guests')}}</label>
        <div class="guest-group">
            <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown"
                    data-text-guest="{{__('Guest')}}"
                    data-text-guests="{{__('Guests')}}"
                    data-text-infant="{{__('Infant')}}"
                    data-text-infants="{{__('Infants')}}"
                    aria-haspopup="true" aria-expanded="false">
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
    $minmax = \App\Controllers\Services\ExperienceController::get_inst()->getMinMaxPrice();
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

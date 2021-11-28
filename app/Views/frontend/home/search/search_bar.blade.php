<?php
enqueue_style('daterangepicker-css');
enqueue_script('daterangepicker-js');
enqueue_script('daterangepicker-lang-js');

enqueue_script('switchery-js');
enqueue_style('switchery-css');

enqueue_style('iconrange-slider');
enqueue_script('iconrange-slider');
?>
<div class="hh-search-bar-wrapper">
    <div class="hh-search-bar-buttons">
        <?php
        $lat = request()->get('lat');
        $lng = request()->get('lng');
        $address = request()->get('address');
        $method = request()->method();
        $post_checkIn = request()->get('checkIn');
        $post_checkOut = request()->get('checkOut');
        $post_num_children = request()->get('num_children');
        $post_num_adult = request()->get('num_adults');
        $post_num_infants = request()->get('num_infants');
        $post_price_filter = request()->get('price_filter');
        $post_amenity_val = request()->get('amenity_val');
        $post_facility_val = request()->get('facility_val');
        $post_hometype_val = request()->get('hometype_val');
        $post_bedrooms = request()->get('bedrooms');
        $post_bathrooms = request()->get('bathrooms');
        $first_minute = request()->get('first_minute');
        $last_minute = request()->get('last_minute');
        ?>
        <div class="hh-button-item button-location form-group">
            <input type="text" id="demo5" name="address" class="form-control typeahead" autocomplete="off" value="{{$address}}" placeholder="{{__('Enter a location ...')}}">
            <input type="hidden" name="lat" value="{{ $lat }}">
            <input type="hidden" name="lng" value="{{ $lng }}">
            <input type="hidden" name="address" value="{{ $address }}">
            <input type="hidden" name="request_method" value="{{$method}}" id="request_method">
            <input type="hidden" id="post_checkIn" value="{{$post_checkIn}}">
            <input type="hidden" id="post_checkOut" value="{{$post_checkOut}}">
            <input type="hidden" id="post_num_children" value="{{$post_num_children}}">
            <input type="hidden" id="post_num_adult" value="{{$post_num_adult}}">
            <input type="hidden" id="post_num_infants" value="{{$post_num_infants}}">
            <input type="hidden" id="post_price_filter" value="{{$post_price_filter}}">
            <input type="hidden" id="post_amenity_val" value="{{$post_amenity_val}}">
            <input type="hidden" id="post_facility_val" value="{{$post_facility_val}}">
            <input type="hidden" id="post_hometype_val" value="{{$post_hometype_val}}">
            <input type="hidden" id="post_bedrooms" value="{{$post_bedrooms}}">
            <input type="hidden" id="post_bathrooms" value="{{$post_bathrooms}}">
            <input type="hidden" id="first_minute" value="{{$first_minute}}">
            <input type="hidden" id="last_minute" value="{{$last_minute}}">
        </div>
        <?php
        $booking_type = request()->get('bookingType', 'per_night');
        ?>
        @if($booking_type == 'per_night')
            <div class="hh-button-item button-date button-date-double form-group"
                 data-date-format="DD.MM.YYYY">
                <span class="text"><?php echo __('Date'); ?></span>
                <?php
                $checkIn = request()->get('checkIn', date('d.m.Y.'));
                $checkOut = request()->get('checkOut', date('d.m.Y.'));
                $checkInOut = request()->get('checkInOut', date('d.m.Y.'));
                ?>
                <input type="hidden" class="check-in-field" name="checkIn" value="{{ $checkIn }}">
                <input type="hidden" class="check-out-field" name="checkOut" value="{{ $checkOut }}">
                <input type="text" class="input-hidden check-in-out-field" name="checkInOut" value="{{ $checkInOut }}">
            </div>
        @endif
        @if($booking_type == 'per_hour')
            <div class="hh-button-item button-date button-date-single button-same-date form-group"
                 data-date-format="{{ hh_date_format_moment() }}">
                <span class="text"><?php echo __('Date'); ?></span>
                <?php
                $checkIn = request()->get('checkInTime', date('d.m.Y.'));
                $checkOut = request()->get('checkOutTime', date('d.m.Y.'));
                $checkInOut = request()->get('checkInOutTime');
                ?>
                <input type="hidden" class="check-in-field" name="checkInTime" value="{{ $checkIn }}">
                <input type="hidden" class="check-out-field" name="checkOutTime" value="{{ $checkOut }}">
                <input type="text" class="input-hidden check-in-out-field" name="checkInOutTime"
                       value="{{ $checkInOut }}">
            </div>
            <?php
            enqueue_script('flatpickr-js');
            enqueue_style('flatpickr-css');
            $startTime = request()->get('startTime', '12:00 AM');
            if (!$startTime) {
                $startTime = '12:00 AM';
            }
            $endTime = request()->get('endTime', '11:30 PM');
            if (!$endTime) {
                $endTime = '11:30 PM';
            }
                $startTime = date(hh_time_format(), strtotime($startTime));
		        $endTime = date(hh_time_format(), strtotime($endTime));
            ?>
            <div class="dropdown dropdown-button dropdown-button-time">
                <div class="hh-button-item button-time form-group" data-toggle="dropdown" aria-haspopup="true"
                     role="article"
                     aria-expanded="false" data-time-format="{{ hh_time_format_picker() }}" data-time-type="{{hh_time_type_picker()}}">
                    <span class="text start">{{$startTime}}</span>
                    {!! get_icon('002_right_arrow', '', '15px') !!}
                    <span class="text end">{{$endTime}}</span>
                    <div class="dropdown-menu">
                        <div class="date-wrapper date-time">
                            <div class="date-render check-in-render"
                                 data-time-format="{{ hh_time_format() }}">
                                <div class="render">{{__('Start Time')}}</div>
                                <input type="hidden" name="startTime" value="{{ $startTime }}"
                                       class="input-checkin input-hidden flatpickr"/>
                            </div>
                            <span class="divider"></span>
                            <div class="date-render check-out-render"
                                 data-time-format="{{ hh_time_format() }}">
                                <div class="render">{{__('End Time')}}</div>
                                <input type="hidden" name="endTime" value="{{$endTime}}"
                                       class="input-checkout input-hidden flatpickr"/>
                            </div>
                        </div>
                        <a href="javascript:void(0)"
                           class="apply-time-filter btn btn-primary btn-xs right">{{__('Apply')}}</a>
                    </div>
                </div>
            </div>
        @endif
        <?php
        $minmax = \App\Controllers\Services\HomeController::get_inst()->getMinMaxPrice();
        $currencySymbol = current_currency('symbol');
        $priceFilter = request()->get('price_filter');
        $priceFilter = explode(';', $priceFilter);
        if (!isset($priceFilter[1]) || $priceFilter[1] == 0) {
            $priceFilter[1] = $minmax['max'];
        }
        $currencyPos = current_currency('position');
        ?>
        <div class="dropdown dropdown-button dropdown-button-price">
            <div class="hh-button-item button-price form-group" data-toggle="dropdown" aria-haspopup="true"
                 role="article"
                 aria-expanded="false">
                <span class="text">{{__('Price')}}</span>
                <div class="dropdown-menu">
                    <input type="text" id="price-filter" name="price_filter" data-plugin="ion-range-slider"
                           data-prefix="{{ $currencyPos == 'left' ? $currencySymbol : ''}}"
                           data-postfix="{{ $currencyPos == 'right' ? $currencySymbol : ''}}"
                           data-min="{{ $minmax['min'] }}"
                           data-max="{{ $minmax['max'] }}"
                           data-from="{{ $priceFilter[0] }}"
                           data-to="{{ $priceFilter[1] }}"
                           data-skin="round">
                </div>
            </div>
        </div>
        <div class="dropdown dropdown-button dropdown-button-more-filter">
            <div class="hh-button-item button-more-filter form-group" data-toggle="dropdown" aria-haspopup="false"
                 role="article"
                 aria-expanded="true">
                <span class="text"><?php echo __('More filters'); ?></span>
                <div class="dropdown-menu">
                    <?php
                        $terms = get_home_terms_filter();
                    ?>
                    @if (!empty($terms))
                        @foreach ($terms as $term_name => $term)
                            <?php
                            $tax = request()->get($term_name);
                            $tax_arr = [];
                            if (!empty($tax)) {
                                $tax_arr = explode(',', $tax);
                            }
                            ?>
                            <div class="item-filter-wrapper" data-type="{{ $term_name }}">
                                @if (!empty($term['items']) && $term['label'] != 'Home Facilities Fields')
                                <div class="label">@if($term['label'] == 'Home Amenity') Amenity @elseif($term['label'] == 'Home Type') Type @else {{ $term['label'] }} @endif</div>
                                    <?php
                                        $idName = str_replace(' ', '-', str_replace(['[', ']'], '_', $term['label']));
                                    ?>
                                    <div class="content">
                                        <div class="row">
                                            @foreach ($term['items'] as $term_id => $term_title)
                                                <?php
                                                $checked = '';
                                                if (in_array($term_id, $tax_arr)) {
                                                    $checked = 'checked';
                                                }
                                                ?>
                                                <div class="col-lg-4 mb-1">
                                                    <div class="item checkbox  checkbox-success ">
                                                        <input type="checkbox" value="{{ $term_id }}"
                                                               id="{{$term_name}}{{ $term_id }}" {{ $checked }}/>
                                                        <label
                                                            for="{{ $term_name }}{{ $term_id }}">{{ $term_title }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="hidden" name="{{ $term_name }}" value="{{ $tax }}"/>
                                @endif
                                
                            </div>
                        @endforeach
                        <div class="" id="special-offer">
                            <div class="label">Special Offer</div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-lg-4 mb-1">
                                        <div class="item checkbox  checkbox-success ">
                                            <input type="checkbox" value="on"
                                                id="first_minute1" name="first_minute"/>
                                            <label
                                                for="first_minute1">FIRST MINUTE</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mb-1">
                                        <div class="item checkbox  checkbox-success ">
                                            <input type="checkbox" value="on"
                                                id="last_minute1" name="last_minute"/>
                                            <label
                                                for="last_minute1">LAST MINUTE</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                                $facilities_list = get_terms('home-advanced'); 
                                ?>
                                <div class="item-filter-wrapper" id="home-facilities">
                                    <!-- <div class="label">Home Facilities</div> -->
                                        
                                    <?php 
                                    foreach ($facilities_list as $key => $value) { ?>
                                        <div class="label">{{$value['title']}}</div>
                                        <div class="content">
                                            <div class="row">
                                            <?php $sub_val = json_decode($value['selection_val']);
                                            
                                                if(!empty($sub_val)){
                                                    foreach ($sub_val as $k=>$item) { 
                                                        foreach ($item as $tmp) {
                                                            $idName = str_replace(' ', '-', str_replace(['[', ']'], '_', $k)); ?>
                                                            <div class="col-lg-4 mb-1">
                                                                <div class="item checkbox  checkbox-success ">
                                                                    <input type="checkbox" value="{{$tmp}}" onchange="checkSearchFacility()"
                                                                        id="{{$idName}}_{{$tmp}}"/>
                                                                    <label
                                                                        for="{{$idName}}_{{$tmp}}">{{ $tmp }}</label>
                                                                </div>
                                                            </div>
                                                        <?php }
                                                        ?>
                                                        
                                                    <?php }
                                                }
                                            ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                       
                                </div>
                        
                    @endif
                    <a href="javascript:void(0)"
                       class="apply-more-filter btn btn-primary btn-xs right">{{__('Apply')}}</a>
                </div>
            </div>
        </div>
        <div class="hh-button-item show-filter form-group" id="show-filter-mobile">
            <span class="text">{{__('Filters')}}</span>
        </div>
        <div class="hh-button-item show-map form-group" id="show-map-mobile">
            <span class="text">{{__('Show map')}}</span>
        </div>
    </div>
    <div class="hh-toggle-map-search">
        <label for="hh-map-toggle-search" class="mr-2">{{__('Show Map')}}</label>
        <input id="hh-map-toggle-search" checked type="checkbox" data-plugin="switchery" data-color="#1bb99a"
               name="toggle_map_search" value="1"/>
    </div>
    <div class="hh-search-bar-toggle"></div>
    <div id="filter-mobile-box" class="filter-mobile-box">
        <div class="render-box">
            <div class="popup-filter-header">
                <span>{{__('Filters')}}</span>
                <div
                    class="popup-filter-close">{!! balanceTags(get_icon('001_close', '#575757', '28px', '28px')) !!}</div>
            </div>
            <div class="popup-filter-content">
                {{--Location--}}
                <div class="filter-item">
                    <p class="filter-item-title">{{__('Location')}}</p>
                    <div class="hh-button-item button-location form-group">
                        <div class="form-control" data-plugin="mapbox-geocoder" data-value="{{ $address }}"
                             data-your-location="{{__('Your Location')}}"
                             data-placeholder="{{__('Enter a location ...')}}"
                             data-lang="{{get_current_language()}}"></div>
                        <div class="map d-none"></div>
                        <input type="hidden" name="lat" value="{{ $lat }}">
                        <input type="hidden" name="lng" value="{{ $lng }}">
                        <input type="hidden" name="address" value="{{ $address }}">
                    </div>
                </div>

                {{--Date--}}
                @if($booking_type == 'per_night')
                    <div class="filter-item">
                        <p class="filter-item-title">{{__('Date')}}</p>
                        <div class="hh-button-item button-date button-date-single form-group"
                             data-date-format="{{ hh_date_format_moment() }}">
                            <span class="text"><?php echo __('Date'); ?></span>
                            <input type="hidden" class="check-in-field" name="checkIn" value="{{ $checkIn }}">
                            <input type="hidden" class="check-out-field" name="checkOut" value="{{ $checkOut }}">
                            <input type="text" class="io-date check-in-out-field" name="checkInOut"
                                   value="{{ $checkInOut }}">
                        </div>
                    </div>
                @endif
                @if($booking_type == 'per_hour')
                    <div class="filter-item">
                        <p class="filter-item-title">{{__('Date')}}</p>
                        <div class="hh-button-item button-date button-date-single form-group"
                             data-date-format="{{ hh_date_format_moment() }}">
                            <span class="text"><?php echo __('Date'); ?></span>
                            <input type="hidden" class="check-in-field" name="checkInTime" value="{{ $checkIn }}">
                            <input type="hidden" class="check-out-field" name="checkOutTime" value="{{ $checkOut }}">
                            <input type="text" class="io-date check-in-out-field" name="checkInOutTime"
                                   value="{{ $checkInOut }}">
                        </div>
                    </div>
                    <div class="filter-item">
                        <p class="filter-item-title">{{__('Time')}}</p>
                        <?php
                        $listTime = list_hours(30);
                        ?>
                        <div class="date-wrapper date-time">
                            <div class="date-render check-in-render"
                                 data-time-format="{{ hh_time_format() }}">
                                <div class="render">{{$startTime}}</div>
                                <div class="dropdown-time">
                                    @foreach($listTime as $key => $value)
                                        <div class="item @if($key == $startTime) active @endif"
                                             data-value="{{ $key }}">{{ $value }}</div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="startTime" value="{{ $startTime }}"
                                       class="input-checkin"/>
                            </div>
                            <span class="divider">{!! get_icon('002_right_arrow', '', '15px') !!}</span>
                            <div class="date-render check-out-render"
                                 data-time-format="{{ hh_time_format() }}">
                                <div class="render">{{$endTime}}</div>
                                <div class="dropdown-time">
                                    @foreach($listTime as $key => $value)
                                        <div class="item @if($key == $endTime) active @endif"
                                             data-value="{{ $key }}">{{ $value }}</div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="endTime" value="{{ $endTime }}"
                                       class="input-checkin"/>
                            </div>
                        </div>
                    </div>
                @endif

                {{--Price--}}
                <div class="filter-item">
                    <p class="filter-item-title">{{__('Price')}}</p>
                    <input type="text" id="price-filter-popup" name="price_filter" data-plugin="ion-range-slider"
                           data-prefix="{{ $currencyPos == 'left' ? $currencySymbol : ''}}"
                           data-postfix="{{ $currencyPos == 'right' ? $currencySymbol : ''}}"
                           data-min="{{ $minmax['min'] }}"
                           data-max="{{ $minmax['max'] }}"
                           data-from="{{ $priceFilter[0] }}"
                           data-to="{{ $priceFilter[1] }}"
                           data-skin="round">
                </div>

                {{--Taxonomy--}}
                @if (!empty($terms))
                    @foreach ($terms as $term_name => $term)
                        <?php
                        $tax = request()->get($term_name);
                        $tax_arr = [];
                        if (!empty($tax)) {
                            $tax_arr = explode(',', $tax);
                        }
                        ?>
                        <div class="filter-item item-filter-wrapper popup-tax-filter" data-type="{{ $term_name }}">
                            <p class="filter-item-title">{{ $term['label'] }}</p>
                            @if (!empty($term['items']) && $term['label'] != 'Home Facilities Fields')
                                <div class="content">
                                    <div class="row">
                                        @foreach ($term['items'] as $term_id => $term_title)
                                            <?php
                                            $checked = '';
                                            if (in_array($term_id, $tax_arr)) {
                                                $checked = 'checked';
                                            }
                                            ?>
                                            <div class="col-sm-4 mb-1">
                                                <div class="item checkbox  checkbox-success ">
                                                    <input type="checkbox" value="{{ $term_id }}"
                                                           id="popup-{{$term_name}}{{ $term_id }}" {{ $checked }}/>
                                                    <label
                                                        for="popup-{{ $term_name }}{{ $term_id }}">{{ $term_title }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <!-- <input type="hidden" name="{{ $term_name }}" value="{{ $tax }}"/> -->
                        </div>
                    @endforeach
                    <?php
                    $facilities_list = get_terms('home-facilities'); ?>
                    <div class="filter-item item-filter-wrapper" id="home-facilities1">
                        <?php foreach ($facilities_list as $key => $value) { ?>
                                <div class="label">{{ $value['title'] }}</div>
                                <?php $idName = str_replace(' ', '-', str_replace(['[', ']'], '_', $value['title']));?>
                                <div class="content">
                                    <div class="row">
                                        <?php $sub_val = json_decode($value['selection_val']);
                                            if(!empty($sub_val)){
                                                foreach ($sub_val as $item) { ?>
                                                    <div class="col-lg-4 mb-1">
                                                        <div class="item checkbox  checkbox-success ">
                                                            <input type="checkbox" value="{{$item}}" onchange="checkFacility()"
                                                                id="{{$idName}}_{{$item}}" {{ $checked }}/>
                                                            <label
                                                                for="{{$idName}}_{{$item}}">{{ $item }}</label>
                                                        </div>
                                                    </div>
                                                <?php }
                                            }
                                        ?>
                                    </div>
                                </div>
                        <?php } ?>
                    </div>
                @endif
            </div>
        </div>
        <div class="popup-filter-footer">
            <div class="view-result">{{__('View filter result')}}</div>
        </div>
    </div>
</div>

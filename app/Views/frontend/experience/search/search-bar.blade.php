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
        ?>
        <div class="hh-button-item button-location form-group">
            <div class="form-control" data-plugin="mapbox-geocoder" data-value="{{ $address }}"
                 data-your-location="{{__('Your Location')}}" data-current-location="0"
                 data-placeholder="{{__('Enter a location ...')}}" data-lang="{{get_current_language()}}"></div>
            <div class="map d-none"></div>
            <input type="hidden" name="lat" value="{{ $lat }}">
            <input type="hidden" name="lng" value="{{ $lng }}">
            <input type="hidden" name="address" value="{{ $address }}">
        </div>
        <div class="hh-button-item button-date button-date-double form-group"
             data-date-format="{{ hh_date_format_moment() }}">
            <span class="text"><?php echo __('Date'); ?></span>
            <?php
            $checkIn = request()->get('checkIn', '');
            $checkOut = request()->get('checkOut', '');
            $checkInOut = request()->get('checkInOut', '');
            ?>
            <input type="hidden" class="check-in-field" name="checkIn" value="{{ $checkIn }}">
            <input type="hidden" class="check-out-field" name="checkOut" value="{{ $checkOut }}">
            <input type="text" class="input-hidden check-in-out-field" name="checkInOut" value="{{ $checkInOut }}">
        </div>
        <?php
        $minmax = \App\Controllers\Services\ExperienceController::get_inst()->getMinMaxPrice();
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
                    $experience_types = get_terms('experience-type', true);
                    $experience_type_value = request()->get('experience-type');
                    $experience_type_arr = [];
                    if (!empty($experience_types)) {
                        $experience_type_arr = explode(',', $experience_types);
                    }
                    ?>
                    <div class="item-filter-wrapper" data-type="experience-type">
                        <div class="label">{{ __('Types') }}</div>
                        <div class="content clearfix">
                            @foreach($experience_types as $term)
                                <div class="item">
                                    <?php
                                    $thumbnail = get_attachment_url($term->term_image, [100, 60]);
                                    ?>
                                    <input type="checkbox" class="d-none" value="{{$term->term_id}}"
                                           id="{{$term->term_name}}-{{$term->term_id}}">
                                    <label for="{{$term->term_name}}-{{$term->term_id}}">
                                        <div class="thumbnail">
                                            <img src="{{ $thumbnail }}"
                                                 alt="{{ get_attachment_alt($term->term_image) }}"
                                                 class="img-fluid">
                                            <span class="title">{{ get_translate($term->term_title) }}</span>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="w-100"></div>
                        <input type="hidden" name="experience-type" value="{{ $experience_type_value }}"/>
                    </div>
                    <?php
                    $terms = get_experience_terms_filter();
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
                                <div class="label">{{ $term['label'] }}</div>
                                @if (!empty($term['items']))
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
                                                    <div class="item checkbox checkbox-success ">
                                                        <input type="checkbox" value="{{ $term_id }}"
                                                               id="{{$term_name}}{{ $term_id }}" {{ $checked }}/>
                                                        <label
                                                            for="{{ $term_name }}{{ $term_id }}">{{ get_translate($term_title) }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <input type="hidden" name="{{ $term_name }}" value="{{ $tax }}"/>
                            </div>
                        @endforeach
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
    <?php
    $showmap = request()->get('showmap', 'yes');
    ?>
    <div class="hh-toggle-map-search">
        <label for="hh-map-toggle-search" class="mr-2">{{__('Show Map')}}</label>
        <input id="hh-map-toggle-search" @if($showmap == 'yes') checked @endif type="checkbox" data-plugin="switchery"
               data-color="#1bb99a"
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
                <div class="item-filter-wrapper filter-item popup-tax-filter" data-type="experience-type">
                    <p class="filter-item-title">{{__('Types')}}</p>
                    <div class="content clearfix">
                        @foreach($experience_types as $term)
                            <div class="item">
                                <?php
                                $thumbnail = get_attachment_url($term->term_image, [100, 60]);
                                ?>
                                <input type="checkbox" class="d-none" value="{{$term->term_id}}"
                                       id="popup-{{$term->term_name}}-{{$term->term_id}}">
                                <label for="popup-{{$term->term_name}}-{{$term->term_id}}">
                                    <div class="thumbnail">
                                        <img src="{{ $thumbnail }}"
                                             alt="{{ get_attachment_alt($term->term_image) }}"
                                             class="img-fluid">
                                        <span class="title">{{ get_translate($term->term_title) }}</span>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="w-100"></div>
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
                            @if (!empty($term['items']))
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
                                                        for="popup-{{ $term_name }}{{ $term_id }}">{{ get_translate($term_title) }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <input type="hidden" name="{{ $term_name }}" value="{{ $tax }}"/>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="popup-filter-footer">
            <div class="view-result">{{__('View filter result')}}</div>
        </div>
    </div>
</div>

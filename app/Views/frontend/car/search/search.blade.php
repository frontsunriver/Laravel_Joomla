@include('frontend.components.header')
<?php
enqueue_style('mapbox-gl-css');
enqueue_style('mapbox-gl-geocoder-css');
enqueue_script('mapbox-gl-js');
enqueue_script('mapbox-gl-geocoder-js');
enqueue_script('search-car-js');
$layout = isset($_GET['layout']) ? $_GET['layout'] : 'grid';
$showmap = (isset($_GET['showmap']) && $_GET['showmap'] == 'no') ? 'no' : 'yes';
$checkIn = isset($_GET['checkIn']) ? $_GET['checkIn'] : '';
$checkOut = isset($_GET['checkOut']) ? $_GET['checkOut'] : '';
$checkInTime = isset($_GET['checkInTime']) ? $_GET['checkInTime'] : '12:00 am';
if ($checkIn == $checkOut) {
    $checkOutTime = isset($_GET['checkOutTime']) ? $_GET['checkOutTime'] : '12:15 am';
} else {
    $checkOutTime = isset($_GET['checkOutTime']) ? $_GET['checkOutTime'] : '12:00 am';
}
?>
<div class="hh-search-result hh-search-result-car"
     data-url="{{ get_car_search_page() }}"
     data-checkin="{{$checkIn}}"
     data-checkout="{{$checkOut}}"
     data-checkin-time="{{$checkInTime}}"
     data-checkout-time="{{$checkOutTime}}">
    @include('frontend.car.search.search-bar')
    <div class="hh-search-content-wrapper @if($showmap == 'no') no-map @endif">
        @include('common.loading')
        <div class="hh-search-results-render" data-url="{{ get_car_search_page() }}">
            <div class="render">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="hh-search-results-string">
                        <span class="item-found">{{__('Searching car...')}}</span>
                    </div>
                    <div class="item-layout">
                        <span class="@if($layout == 'grid') active @endif" data-layout="grid"><i
                                class="ti-layout-grid2"></i></span>
                        <span class="@if($layout == 'list') active @endif" data-layout="list"><i
                                class="ti-view-list-alt"></i></span>
                    </div>
                </div>
                <div class="hh-search-content row">
                    <div class="service-item list"></div>
                </div>
                <div class="hh-search-pagination mt-0"></div>
            </div>
        </div>
        <div class="hh-search-results-map">
            <?php
            $lat = request()->get('lat');
            $lng = request()->get('lng');
            $zoom = request()->get('zoom', 10);
            $in_map = true;
            ?>
            <div class="hh-mapbox-search" data-lat="{{ $lat }}"
                 data-lng="{{ $lng }}" data-zoom="{{ $zoom }}"></div>
            <div class="hh-close-map-popup" id="hide-map-mobile">{{__('Close')}}</div>
            <div class="hh-map-tooltip">
                <div class="checkbox checkbox-success">
                    <input id="chk-map-move" type="checkbox" name="map_move" value="1">
                    <label for="chk-map-move">{{__('Search as I move the map')}}</label>
                </div>
                @include('common.loading')
            </div>
        </div>
    </div>
</div>
@include('frontend.components.footer')

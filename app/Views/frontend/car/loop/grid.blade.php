<?php
if (!isset($class_wrapper))
    $class_wrapper = '';

$url_query = get_date_query_string(request()->all());
$url = get_car_permalink($item->post_id, $item->post_slug) . $url_query;
?>
<div class="{{$class_wrapper}}">
    <div class="hh-service-item car grid" data-plugin="matchHeight" data-lng="{{ $item->location_lng }}"
         data-lat="{{ $item->location_lat }}" data-id="{{ $item->post_id }}">
        <a href="{{ $url }}">
            <div class="thumbnail">
                @if($item->is_featured == 'on')
                    <div class="is-featured">{{ get_option('featured_text', __('Featured')) }}</div>
                @endif
                <div class="thumbnail-outer">
                    <div class="thumbnail-inner">
                        <img src="{{ get_attachment_url($item->thumbnail_id, [650, 550]) }}"
                             alt="{{ get_attachment_alt($item->thumbnail_id ) }}"
                             class="img-fluid">
                    </div>
                </div>
            </div>
        </a>
        <div class="detail">
            <?php
            $car_type = '';
            if (!empty($item->car_type)) {
                $type = get_term_by('id', $item->car_type);
                if (!is_null($type)) {
                    $car_type = get_translate($type->term_title);
                }
            }
            ?>
            @if(!empty($car_type))
                <div class="car-type">
                    {{$car_type}}
                </div>
            @endif
            <h2 class="title text-overflow"><a
                    href="{{ $url }}">{{ get_translate($item->post_title) }}</a></h2>
            @if($item->location_address)
                <p class="text-muted text-overflow mb-1"><i class="fe-map-pin mr-1"></i>
                    {{ get_short_address($item) }}
                    @if(isset($show_distance) && $show_distance && isset($item->distance))
                        <?php
                        $distance = round($item->distance, 2);
                        ?>
                        <strong>({{ $distance }}{{__('km')}})</strong>
                    @endif
                </p>
            @endif
            <div class="facilities d-flex align-items-center justify-content-between">
                <div class="item" data-toggle="tooltip" data-original-title="{{__('No.Passenger')}}">
                    <span class="car-icon">{!! get_icon('ico_passenger', '', '26px', '26px');  !!}</span>
                    <span class="car-attribute">{{ (int)$item->passenger }}</span>
                </div>
                <div class="item" data-toggle="tooltip" data-original-title="{{__('Gear Shift')}}">
                    <span class="car-icon">{!! get_icon('ico_gear_shift', '', '26px', '26px');  !!}</span>
                    <span class="car-attribute">{{ get_translate($item->gear_shift) }}</span>
                </div>
                <div class="item" data-toggle="tooltip" data-original-title="{{__('Baggage')}}">
                    <span class="car-icon">{!! get_icon('ico_baggage', '', '26px', '26px');  !!}</span>
                    <span class="car-attribute">{{ (int)$item->baggage }}</span>
                </div>
                <div class="item" data-toggle="tooltip" data-original-title="{{__('No.Door')}}">
                    <span class="car-icon">{!! get_icon('ico_car_door', '', '26px', '26px');  !!}</span>
                    <span class="car-attribute">{{ (int)$item->door }}</span>
                </div>
            </div>
            <div class="w-100 mt-1"></div>
            <div class="footer-action d-flex align-items-center justify-content-between">
                @if(enable_review())
                    @include('frontend.components.star', ['rate' => $item->rating, 'show_text' => true, 'style' => 2])
                @endif
                <div class="price-wrapper {{ (empty($item->rating) || !enable_review()) ? 'left' : '' }}">
                    <span class="price">{{ convert_price($item->base_price) }}</span><span
                        class="unit">/{{get_car_unit()}}</span>
                </div>
            </div>
        </div>
    </div>
</div>

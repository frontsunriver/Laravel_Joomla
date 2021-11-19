<?php
if (!isset($class_wrapper)) {
    $class_wrapper = '';
}

$url_query = get_date_query_string(request()->all());
$url = get_car_permalink($item->post_id, $item->post_slug) . $url_query;
?>
<div class="{{$class_wrapper}}">
    <div class="hh-service-item car list w-100" data-lng="{{ $item->location_lng }}"
         data-lat="{{ $item->location_lat }}" data-id="{{ $item->post_id }}">
        <div class="row">
            <div class="col-12 col-lg-5">
                <a href="{{ $url }}">
                    <div class="thumbnail">
                        <div class="thumbnail-outer">
                            <div class="thumbnail-inner">
                                <img src="{{ get_attachment_url($item->thumbnail_id, [650, 550]) }}"
                                     alt="{{ get_attachment_alt($item->thumbnail_id ) }}"
                                     class="img-fluid">
                            </div>
                        </div>
                        @if($item->is_featured == 'on')
                            <div class="is-featured">{{ get_option('featured_text', __('Featured')) }}</div>
                        @endif
                    </div>
                </a>
            </div>
            <div class="col-12 col-lg-7">
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
                    <a class="title mb-1 mt-1 d-block" href="{{ $url }}">
                        {{ get_translate($item->post_title) }}</a>
                    <p class="address text-overflow"><i
                            class="fe-map-pin mr-1"></i>{{ get_translate($item->location_address) }}</p>
                    <div class="facilities d-flex align-items-center justify-content-between">
                        <div class="item" data-toggle="tooltip" data-original-title="{{__('No.Passenger')}}">
                            <span class="car-icon">{!! get_icon('ico_passenger', '', '26px', '26px');  !!}</span>
                            {{ (int)$item->passenger }}
                        </div>
                        <div class="item" data-toggle="tooltip" data-original-title="{{__('Gear Shift')}}">
                            <span class="car-icon">{!! get_icon('ico_gear_shift', '', '26px', '26px');  !!}</span>
                            {{ get_translate($item->gear_shift) }}
                        </div>
                        <div class="item" data-toggle="tooltip" data-original-title="{{__('Baggage')}}">
                            <span class="car-icon">{!! get_icon('ico_baggage', '', '26px', '26px');  !!}</span>
                            {{ (int)$item->baggage }}
                        </div>
                        <div class="item" data-toggle="tooltip" data-original-title="{{__('No.Door')}}">
                            <span class="car-icon">{!! get_icon('ico_car_door', '', '26px', '26px');  !!}</span>
                            {{ (int)$item->door }}
                        </div>
                    </div>
                    <div class="w-100 mt-1"></div>
                    <div class="footer-content d-flex align-items-center justify-content-between">
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
    </div>
</div>

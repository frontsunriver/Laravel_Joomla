<?php
global $post;
enqueue_script('accounting-js');

$langs = get_languages();
?>
<div id="hh-dashboard-service-preview" class="card-box">
    <div class="thumbnail">
        <?php
        $isFeatured = $post->thumbnail_id;
        $classFeatured = ($isFeatured) ? 'is-featured' : '';
        $img = get_attachment_url($isFeatured, [450, 320], true);
        $avatar = get_user_avatar($post->author, [50, 50]);
        ?>
        <img src="{{ $img }}"
             alt="{{__('Placeholder')}}"
             class="img-responsive img-featured">
        <img src="{{ $avatar }}"
             alt="{{__('Image Profile')}}"
             class="img-responsive avatar">
    </div>
    <div class="content pb-3">
        <h2 class="title"
            data-hh-bind-default="{{__('Name of Car')}}">
            @if(!empty($langs))
                @foreach($langs as $key => $item)
                    <a class="c-black has-translation {{$key != 0 ? 'hidden' : ''}}"
                       data-hh-bind-from="#post_title_{{$item}}" target="_blank"
                       href="{{ get_car_permalink($post->post_id) }}" data-lang="{{$item}}">{{__('Name of Car')}}</a>
                @endforeach
            @else
                <a class="c-black" data-hh-bind-from="#post_title" target="_blank"
                   href="{{ get_car_permalink($post->post_id) }}">{{__('Name of Car')}}</a>
            @endif
        </h2>
        @if(!empty($langs))
            <p>
                <i class="fe-map-pin"></i>
                @foreach($langs as $key => $item)
                    <span class="address has-translation {{$key != 0 ? 'hidden' : ''}}"
                          data-hh-bind-from="#location_address_{{$item}}" data-lang="{{$item}}"
                          data-hh-bind-default="{{__('Address')}}">{{__('Address')}}</span>
                @endforeach
            </p>
        @else
            <p><i class="fe-map-pin"></i>
                <span class="address" data-hh-bind-from="#location_address"
                      data-hh-bind-default="{{__('Address')}}">{{__('Address')}}</span>
            </p>
        @endif
        <div class="facilities d-flex align-items-center justify-content-between">
            <div class="item max-people">
                <div class="d-block text-center">
                    <span class="car-icon d-block">{!! get_icon('ico_passenger', '', '26px', '26px');  !!}</span>
                    <span class="text-center mt-1" data-hh-bind-from="#passenger" data-hh-bind-default="1">1</span>
                </div>
            </div>
            <div class="item max-bedrooms">
                <div class="d-block text-center">
                    <span class="car-icon d-block">{!! get_icon('ico_gear_shift', '', '26px', '26px');  !!}</span>
                    <span class="text-center mt-1" data-hh-bind-from="#gear_shift"
                          data-hh-bind-default="{{__('Auto')}}">{{__('Auto')}}</span>
                </div>
            </div>
            <div class="item max-baths">
                <div class="d-block text-center">
                    <span class="car-icon d-block">{!! get_icon('ico_baggage', '', '26px', '26px');  !!}</span>
                    <span class="text-center mt-1" data-hh-bind-from="#baggage" data-hh-bind-default="8">8</span>
                </div>
            </div>
            <div class="item max-baths">
                <div class="d-block text-center">
                    <span class="car-icon d-block">{!! get_icon('ico_car_door', '', '26px', '26px');  !!}</span>
                    <span class="text-center mt-1" data-hh-bind-from="#door" data-hh-bind-default="4">4</span>
                </div>
            </div>
        </div>
        <div class="clearfix">
            <div class="reviews">
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo '<i class="fe-star-on"></i>';
                }
                ?>
                <span class="rate">{{__('5 Reviews')}}</span>
            </div>
            <div class="prices">
                <span class="price"
                      data-hh-bind-from="#base_price"
                      data-hh-accounting="1"
                      data-hh-bind-default="0">0</span>
                <span class="unit">/{{get_car_unit()}}</span>
            </div>
        </div>
    </div>
</div>

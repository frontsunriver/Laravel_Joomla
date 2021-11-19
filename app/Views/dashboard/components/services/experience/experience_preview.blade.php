<?php
global $post;
enqueue_script('accounting-js');

$langs = get_languages();
?>
<div id="hh-dashboard-service-preview" class="card-box experience-preview">
    <div class="thumbnail">
        <?php
        $isFeatured = $post->thumbnail_id;
        $img = get_attachment_url($isFeatured, [400, 500], true);
        ?>
        <img src="{{ $img }}"
             alt="{{__('Placeholder')}}"
             class="img-responsive img-featured">
    </div>
    <div class="content">
        <div class="address">
            @if(!empty($langs))
                @foreach($langs as $key => $item)
                    <span class="city has-translation {{$key != 0 ? 'hidden' : ''}}"
                          data-hh-bind-default="{{__('City')}}"
                          data-hh-bind-from="#location_city_{{$item}}">{{__('City')}}</span>,&nbsp;
                    <span class="country has-translation {{$key != 0 ? 'hidden' : ''}}"
                          data-hh-bind-default="{{__('Country')}}"
                          data-hh-bind-from="#location_country_{{$item}}">{{__('Country')}}</span>
                @endforeach
            @else
                <span class="city"
                      data-hh-bind-default="{{__('City')}}"
                      data-hh-bind-from="#location_city">{{__('City')}}</span>,&nbsp;
                <span class="country"
                      data-hh-bind-default="{{__('Country')}}"
                      data-hh-bind-from="#location_country">{{__('Country')}}</span>
            @endif
        </div>
        <h2 class="title"
            data-hh-bind-default="{{__('Title of Experience')}}">
            <span class="is-featured featured-icon"
                  title="{{__('Featured')}}">{!! balanceTags(get_icon('001_diamond', '', '18px', '20px')) !!}</span>
            @if(!empty($langs))
                @foreach($langs as $key => $item)
                    <a class="c-black has-translation {{$key != 0 ? 'hidden' : ''}}"
                       data-hh-bind-from="#post_title_{{$item}}" target="_blank"
                       href="{{ get_the_permalink($post->post_id,$post->post_slug, 'experience') }}"
                       data-lang="{{$item}}">{{__('Title of Experience')}}</a>
                @endforeach
            @else
                <a class="c-black" data-hh-bind-from="#post_title" target="_blank"
                   href="{{ get_the_permalink($post->post_id,$post->post_slug, 'experience') }}">{{__('Title of Experience')}}</a>
            @endif
        </h2>
        <div class="duration d-flex align-items-center">
                <span class="mr-1"> {!! get_icon('001_clock', '#4a4a4a', '15px', '15px') !!}
                </span>
            @if(!empty($langs))
                @foreach($langs as $key => $item)
                    <span class="duration-render has-translation {{$key != 0 ? 'hidden' : ''}}"
                          data-hh-bind-default="{{__('Duration')}}"
                          data-hh-bind-from="#durations_{{$item}}">{{__('Duration')}}</span>
                @endforeach
            @else
                <span class="duration-render"
                      data-hh-bind-default="{{__('Duration')}}"
                      data-hh-bind-from="#durations">{{__('Duration')}}</span>
            @endif
        </div>
        <div class="clearfix">
            <div class="reviews">
                5.0 <i class="fe-star-on"></i>
            </div>
            <div class="prices">
                <span class="unit c-black">{{__('From')}}</span>
                <span class="price"
                      data-hh-bind-from="#price_categories-price-adult"
                      data-hh-accounting="1"
                      data-hh-bind-default="0">0</span>

            </div>
        </div>
    </div>
</div>

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
    <div class="content">
        <h2 class="title"
            data-hh-bind-default="{{__('Title of Home')}}">
            @if(!empty($langs))
                @foreach($langs as $key => $item)
                    <a class="c-black has-translation {{$key != 0 ? 'hidden' : ''}}"
                       data-hh-bind-from="#post_title_{{$item}}" target="_blank"
                       href="{{ get_the_permalink($post->post_id) }}" data-lang="{{$item}}">{{__('Title of Home')}}</a>
                @endforeach
            @else
                <a class="c-black" data-hh-bind-from="#post_title" target="_blank"
                   href="{{ get_the_permalink($post->post_id) }}">{{__('Title of Home')}}</a>
            @endif
        </h2>
        @if(!empty($langs))
            @foreach($langs as $key => $item)
                <p class="address has-translation {{$key != 0 ? 'hidden' : ''}}"
                   data-hh-bind-from="#location_address_{{$item}}" data-lang="{{$item}}"
                   data-hh-bind-default="{{__('Address')}}">{{__('Address')}}</p>
            @endforeach
        @else
            <p class="address" data-hh-bind-from="#location_address"
               data-hh-bind-default="{{__('Address')}}">{{__('Address')}}</p>
        @endif
        <div class="facilities">
            <div class="item max-people">
                {!! __(':number guests', ['number' => '<span data-hh-bind-from="#number_of_guest"
                      data-hh-bind-default="0">0</span>']) !!}
            </div>
            <div class="item max-bedrooms">
                {!! __(':number beds', ['number' => '<span data-hh-bind-from="#number_of_bedrooms"
                      data-hh-bind-default="0">0</span>']) !!}
            </div>
            <div class="item max-baths">
                {!! __(':number baths', ['number' => '<span data-hh-bind-from="#number_of_bathrooms" data-hh-bind-default="0">0</span>']) !!}
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
                <span class="price" data-hh-bind-from="#base_price"
                      data-hh-accounting="1"
                      data-hh-bind-default="0">0</span>
                <span class="unit">{{__('/night')}}</span>
            </div>
        </div>
    </div>
</div>

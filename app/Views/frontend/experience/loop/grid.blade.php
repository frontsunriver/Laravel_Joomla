<div class="hh-service-item experience grid" data-plugin="matchHeight" data-lng="{{ $item->location_lng }}"
     data-lat="{{ $item->location_lat }}" data-id="{{ $item->post_id }}">
    <a href="{{ get_the_permalink($item->post_id, $item->post_slug, 'experience') }}">
        <div class="thumbnail">
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
        $short_address = get_short_address($item);
        ?>
        @if(!empty($short_address))
            <div class="address">
                {{ $short_address }}
                @if(isset($show_distance) && $show_distance && isset($item->distance))
                    <?php
                    $distance = round($item->distance, 2);
                    ?>
                    <strong>({{ $distance }}{{__('km')}})</strong>
                @endif
            </div>
        @endif
        <a class="title mb-1" href="{{ get_experience_permalink($item->post_id, $item->post_slug) }}">
            @if($item->is_featured == 'on')
                <span class="is-featured featured-icon"
                      title="{{__('Featured')}}">{!! balanceTags(get_icon('001_diamond', '', '15px', '18px')) !!}</span>
            @endif
            {{ get_translate($item->post_title) }}</a>
        <?php
        $duration = $item->durations;
        ?>
        @if(!empty($duration))
            <div class="duration d-flex align-items-center">
                <span class="mr-1"> {!! get_icon('001_clock', '#4a4a4a', '15px', '15px') !!}
                </span>
                {{ get_translate($duration) }}
            </div>
        @endif
        <div class="w-100 mt-1"></div>
        <div class="d-flex align-items-center justify-content-between">
            <?php
            $rate = $item->review_count;
            ?>
            @if(enable_review() && $rate)
                <div class="count-reviews">
                    {{ number_format(round((float)$item->rating, 1), 1) }} <i class="fas fa-star"></i>
                </div>
            @endif
            <div class="price-wrapper {{ (!$rate || !enable_review()) ? 'left' : '' }}">
                <span class="unit">{{__('From')}}
                    <span class="price">{{ convert_price($item->base_price) }}</span>
                </span>
            </div>
        </div>
    </div>
</div>

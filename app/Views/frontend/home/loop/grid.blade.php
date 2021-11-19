<div class="hh-service-item home grid" data-plugin="matchHeight">
    <a href="{{ get_the_permalink($item->post_id, $item->post_slug) }}">
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
            <div class="author">
                <img src="{{ get_user_avatar($item->author, [45, 45]) }}" alt="{{ get_username($item->author) }}">
            </div>
        </div>
    </a>
    <div class="detail">
        <h2 class="title text-overflow"><a
                href="{{ get_home_permalink($item->post_id, $item->post_slug) }}">{{ get_translate($item->post_title) }}</a>
        </h2>
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
        <div class="facilities">
            <div class="item max-people">
                {{ _n("[0::%s guests][1::%s guest][2::%s guests]", (int)$item->number_of_guest) }}
            </div>
            <div class="item max-bedrooms">
                {{ _n("[0::%s bedrooms][1::%s bedroom][2::%s bedrooms]", (int)$item->number_of_bedrooms) }}
            </div>
            <div class="item max-baths">
                {{ _n("[0::%s bathrooms][1::%s bathroom][2::%s bathrooms]", (int)$item->number_of_bathrooms) }}
            </div>
        </div>
        <div class="w-100 mt-1"></div>
        @if(enable_review())
            @include('frontend.components.star', ['rate' => $item->rating, 'show_text' => true])
        @endif
        <div class="price-wrapper {{ (empty($item->rating) || !enable_review()) ? 'left' : '' }}">
            <span class="price">{{ convert_price($item->base_price) }}</span><span
                class="unit">/{{get_home_unit($item)}}</span>
        </div>
    </div>
</div>

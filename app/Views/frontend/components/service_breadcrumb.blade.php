<?php
    global $post;
    $address = get_short_address($post);
    $lat = $post->location_lat;
    $lng = $post->location_lng;
    $post_type = isset($post_type) ? $post_type : 'home';
    $search_url = add_query_arg(['lat' => $lat, 'lng' => $lng, 'address' => $address], get_search_page($post_type));
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">
            <a href="{{ url('/') }}">{{__('Home')}}</a>
        </li>
        @if(!empty($lat) && !empty($lng) && !empty($address))
            <li class="breadcrumb-item">
                <a href="{{ esc_url($search_url) }}">{{ $address }}</a>
            </li>
        @endif
        <li class="breadcrumb-item" aria-current="page">{{ get_translate($post->post_title) }}</li>
    </ol>
</nav>

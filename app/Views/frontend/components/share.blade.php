<?php
    if (!isset($share)) {
        return false;
    }
?>
<div class="hh-sharing">
    @if(!empty($share['facebook']))
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $share['facebook']['url'] }}"
           class="share-item">{!! get_icon('001_facebook') !!}</a>
    @endif
    @if(!empty($share['twitter']))
        <a href="https://twitter.com/home?status={{ $share['twitter']['url'] }}"
           class="share-item">{!! get_icon('002_twitter') !!}</a>
    @endif
    @if(!empty($share['pinterest']))
        <a href="https://pinterest.com/pin/create/button/?url={{ $share['pinterest']['url'] }}&media={{ $share['pinterest']['img'] }}&description={{ urlencode($share['pinterest']['description']) }}"
           class="share-item">{!! get_icon('003_pinterest') !!}</a>
    @endif
</div>

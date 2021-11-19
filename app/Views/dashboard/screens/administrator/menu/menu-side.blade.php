<?php
    $items = ['page', 'post', 'category', 'tag', 'link', 'home', 'experience', 'car'];
?>
<h4 class="header-title mb-0">{{__('Add menu item')}}</h4>
@foreach($items as $item)
    @include('dashboard.screens.administrator.menu.item-' . $item)
@endforeach

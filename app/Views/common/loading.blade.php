<?php
    $class = isset($class) ? $class : '';
    $class_extend = '';
    if (isset($in_map)) {
        $class_extend = '-map';
    }
?>
<div class="hh-loading{{ $class_extend }} {{ $class }}" style="@if(isset($zindex)) z-index: {{$zindex}} @endif">
    <div class="lds-ellipsis">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

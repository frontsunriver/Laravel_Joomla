<?php
    $class = isset($class) ? $class : '';
    $class_extend = '';
    if (isset($in_map)) {
        $class_extend = '-map';
    }
?>
<div class="hh-loading card-disabled{{ $class_extend }} {{ $class }}">
    <div class="card-portlets-loader"></div>
</div>

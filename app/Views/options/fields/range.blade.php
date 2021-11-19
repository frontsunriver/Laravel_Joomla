<?php
    $layout = (!empty($layout)) ? $layout : 'col-12';
    if (empty($value)) {
        $value = $std;
    }
    $idName = str_replace(['[', ']'], '_', $id);

    enqueue_style('range-slider');
    enqueue_script('range-slider');

?>
<div id="setting-{{ $idName }}" data-condition="{{ $condition }}"
     data-unique="{{ $unique }}"
     data-operator="{{ $operation }}"
     class="form-group mb-3 col {{ $layout }} field-{{ $type }}">
    <label for="{{ $idName }}">
        {{ __($label) }}
        @if (!empty($desc))
            <i class="dripicons-information field-desc" data-toggle="popover" data-placement="right"
               data-trigger="hover"
               data-content="{{ __($desc) }}"></i>
        @endif
    </label>
    <input type="range" id="{{ $idName }}"
           data-validation="{{ $validation }}"
           class="@if(!empty($validation)) has-validation @endif"
           min="{{ $minlength }}"
           max="{{ $maxlength['max-length'] }}"
           step="1"
           data-orientation="horizontal"
           data-rangeslider
           name="{{ $id }}" value="{{ $value }}">
    <input type="number"
           class="form-control"
           min="{{ $minlength }}"
           max="{{ $maxlength['max-length'] }}"
           step="1" value="{{ $value }}">
</div>
@if($break)
    <div class="w-100"></div> @endif

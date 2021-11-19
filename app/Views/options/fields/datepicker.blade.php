<?php
    $layout = (!empty($layout)) ? $layout : 'col-12';
    if (empty($value)) {
        $value = $std;
    }
    $idName = str_replace(['[', ']'], '_', $id);

    enqueue_script('flatpickr-js');
    enqueue_style('flatpickr-css');

    if ($min_date == -1) {
        $min_date = '';
    }
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
    <input type="text" id="{{ $idName }}" class="form-control @if (!empty($validation)) has-validation @endif"
           data-plugin="datepicker"
           data-min-date="{{ $min_date }}"
           data-validation="{{ $validation }}"
           name="{{ $id }}" value="{{ $value }}">
</div>
@if($break)
    <div class="w-100"></div> @endif

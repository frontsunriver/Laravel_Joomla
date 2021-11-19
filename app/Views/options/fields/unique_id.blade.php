<?php
    $layout = (!empty($layout)) ? $layout : 'col-12';
    if (empty($value)) {
        $value = $std;
    }
    $idName = str_replace(['[', ']'], '_', $id);
    if (empty($value)) {
        $value = time() . uniqid();
    }

?>
<div id="setting-{{ $idName }}" data-condition="{{ $condition }}"
     data-unique="{{ $unique }}"
     data-bind-from="{{ $bind_from }}"
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
    <input type="text" id="{{ $idName }}"
           data-validation="{{ $validation }}" readonly
           class="form-control @if (isset($maxlengthHtml)) has-maxLength @endif @if (!empty($validation)) has-validation @endif"
           name="{{ $id }}" value="{{ $value }}">
</div>
@if($break)
    <div class="w-100"></div> @endif

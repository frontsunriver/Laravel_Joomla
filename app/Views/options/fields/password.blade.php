<?php
    $layout = (!empty($layout)) ? $layout : 'col-12';
    if (empty($value)) {
        $value = $std;
    }
    if (!empty($maxlength) && is_array($maxlength)) {
        $maxlengthHtml = '';
        foreach ($maxlength as $k => $v) {
            if ($k == 'max-length') {
                $maxlengthHtml .= ' maxlength="' . $v . '" ';
            } else {
                $maxlengthHtml .= ' data-' . $k . '="' . $v . '" ';
            }
        }
    }
    $idName = str_replace(['[', ']'], '_', $id);
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
    <div class="input-group">
        <input type="text" id="{{ $idName }}"
               data-validation="{{ $validation }}" data-plugin="password"
               class="form-control @if (isset($maxlengthHtml)) has-maxLength @endif @if (!empty($validation)) has-validation @endif"
               @if (isset($maxlengthHtml)) {{ $maxlengthHtml }} @endif
               name="{{ $id }}" value="{{ $value }}">
        <div class="input-group-append">
            <button class="btn btn-dark waves-effect waves-light" type="button">{{__('Create Password')}}</button>
        </div>
    </div>

</div>
@if($break)
    <div class="w-100"></div> @endif

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
           data-validation="{{ $validation }}"
           class="form-control @if(!empty($maxlengthHtml)) has-maxLength @endif  @if(!empty($validation)) has-validation @endif hh-icon-input"
           data-plugin="fonticon"
           @if (isset($maxlengthHtml)) {!! balanceTags($maxlengthHtml) !!} @endif
           data-action="{{ dashboard_url('get-font-icon') }}"
           name="{{ $id }}" value="{{ $value }}">
</div>
@if($break)
    <div class="w-100"></div> @endif

<?php
$layout = (!empty($layout)) ? $layout : 'col-12';
if ($value === '') {
    $value = $std;
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
    </label><br/>
    @foreach ($choices as $key => $title)
        <div class="radio radio-success {{ $style }}">
            <input type="radio"
                   name="{{ $id }}"
                   value="{{ $key }}"
                   @if($key == $value) checked @endif
                   id="{{ $idName }}-{{ $key }}">
            <label for="{{ $id }}-{{ $key }}">{{ __($title) }}</label>
        </div>
    @endforeach
</div>
@if($break)
    <div class="w-100"></div> @endif

<?php
    $layout = (!empty($layout)) ? $layout : 'col-12';
    if (empty($value)) {
        $value = $std;
    }
    $idName = str_replace(['[', ']'], '_', $id);

    enqueue_script('switchery-js');
    enqueue_style('switchery-css');
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
    <input type="checkbox" id="{{ $idName }}" name="{{ $id }}"
           data-plugin="switchery" data-color="#1abc9c" value="on" @if($value == 'on') checked @endif/>
</div>
@if($break)
    <div class="w-100"></div> @endif

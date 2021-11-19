<?php
$layout = (!empty($layout)) ? $layout : 'col-12';
if ($value === '') {
    $value = $std;
}
$idName = str_replace(['[', ']'], '_', $id);

enqueue_style('daterangepicker-css');
enqueue_script('daterangepicker-js');
enqueue_script('daterangepicker-lang-js');
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
    <div class="hh-availability">
        <input type="hidden" class="calendar_input"
               data-service-type="{{$post_type}}"
               data-id="{{$post_id}}" data-action="{{ dashboard_url('get-inventory') }}">
    </div>
</div>
@if($break)
    <div class="w-100"></div>
@endif
@include('options.fields.components.availability-'.$post_type, ['post_id' => $post_id])

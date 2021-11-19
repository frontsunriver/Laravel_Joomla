<?php
    $layout = (!empty($layout)) ? $layout : 'col-12';
    if (empty($value)) {
        $value = $std;
    }
    $idName = str_replace(['[', ']'], '_', $id);
?>
<div id="setting-{{ $idName }}" data-condition="{{ $condition }}"
     data-unique="{{ $unique }}"
     data-operator="{{ $operation }}"
     data-hh-bind-value-from="{{$bind_value_from}}"
     class="form-group mb-3 col {{ $layout }} field-{{ $type }}">
    <label for="{{ $idName }}">
        {{ __($label) }}
        @if (!empty($desc))
            <i class="dripicons-information field-desc" data-toggle="popover" data-placement="right"
               data-trigger="hover"
               data-content="{{ __($desc) }}"></i>
        @endif
    </label>
    <div class="input-group input-group--export-ical">
        <div class="input-group-prepend">
            <button class="btn btn-dark waves-effect waves-light" data-message-title="{{__('System Alert')}}"
                    data-message-message="{{__('Copied')}}" type="button">{{__('Copy Url')}}</button>
        </div>
        <input type="text" class="form-control" name="export_ical" value="{{get_ical_url($post_id, $post_type)}}">
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif

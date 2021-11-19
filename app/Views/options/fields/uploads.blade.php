<?php
$layout = (!empty($layout)) ? $layout : 'col-12';
if (empty($value)) {
    $value = $std;
}
$oldValue = $value;
$value = (empty($value)) ? [] : explode(',', $value);
$idName = str_replace(['[', ']'], '_', $id);

enqueue_style('dropzone-css');
enqueue_script('dropzone-js');
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
    </label> <br/>
    <div class="hh-upload-wrapper">
        <div class="hh-upload-wrapper clearfix">
            <div class="attachments">
                @if (!empty($value))
                    @foreach($value as $image_id)
                        <?php $url = get_attachment_url($image_id, 'medium', true); ?>
                        <div class="attachment-item">
                            <div class="thumbnail"><img src="{{ $url }}" alt="{{__('Image')}}"></div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="mt-1">
                <a href="javascript:void(0);" class="remove-attachment">{{__('Remove')}}</a>
                <a href="javascript:void(0);" class="add-attachments"
                   title="{{__('Add Image')}}"
                   data-text="{{__('Add Image')}}"
                   data-url="{{ dashboard_url('all-media') }}">{{__('Add Images')}}</a>
                <input type="hidden" class="upload_value input-uploads" value="{{ $oldValue }}"
                       name="{{ $id }}" data-url="{{ dashboard_url('get-attachments') }}">
            </div>
        </div>
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif


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

    if (!isset($post_type)) {
        $post_type = 'home';
    }
    $post_type_info = post_type_info($post_type);
?>
<div id="setting-{{ $idName }}" data-condition="{{ $condition }}"
     data-unique="{{ $unique }}"
     data-operator="{{ $operation }}"
     class="form-group mb-3 col {{ $layout }} field-{{ $type }}">
    <a class="link" data-toggle="collapse" href="#field-{{ $type }}-collapse" aria-expanded="true">
        <i class="fe-link mr-1"></i>{{__('Change the permalink')}}
    </a>
    <div class="collapse show mt-2" id="field-{{ $type }}-collapse">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"
                      id="basic-addon1">{{ get_preview_permalink($post_type, $post_id)  }}/</span>
            </div>
            <input type="text" id="{{ $idName }}"
                   data-validation="{{ $validation }}" placeholder="{{__('leave empty to apply from the title')}}"
                   class="form-control @if(!empty($maxlengthHtml)) has-maxLength @endif  @if(!empty($validation)) has-validation @endif"
                   @if (isset($maxlengthHtml)) {!! balanceTags($maxlengthHtml) !!} @endif
                   name="{{ $id }}" value="{{ $value }}">
        </div>
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif

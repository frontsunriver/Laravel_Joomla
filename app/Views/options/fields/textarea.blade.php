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
    $langs = $translation == false ? [""] : get_languages_field();
    $class_seo = $seo_detect ? 'seo-detect' : '';
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
    @foreach($langs as $key => $item)
        <textarea @if(!empty($item)) data-lang="{{$item}}" @endif name="{{ $id }}{{get_lang_suffix($item)}}"
                  id="{{ $idName }}{{get_lang_suffix($item)}}"
                  data-validation="{{ $validation }}"
                  data-post-id="{{$post_id}}"
                  data-seo-detect="{{$seo_put_to}}"
                  class="form-control {{$class_seo}} {{get_lang_class($key, $item)}} @if (isset($maxlengthHtml)) has-maxLength @endif @if (!empty($validation)) has-validation @endif" @if (isset($maxlengthHtml)) {!! balanceTags($maxlengthHtml) !!} @endif>{{ get_translate($value, $item) }}</textarea>
    @endforeach
</div>
@if($break)
    <div class="w-100"></div> @endif

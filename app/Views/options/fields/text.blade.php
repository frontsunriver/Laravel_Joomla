<?php
$layout = (!empty($layout)) ? $layout : 'col-12';
if ($value === '') {
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
$idName = str_replace(' ', '-', str_replace(['[', ']'], '_', $id));
$langs = $translation == false ? [""] : get_languages_field();
if(!empty($choices)){
    if (!is_array($choices)){
        $choicesTemp = explode(':', $choices);
        if ($choicesTemp[0] == 'terms') {
            if($choicesTemp[1] == 'home-distance'){
                if(!empty($value)) {
                    $tmpValue = json_decode($value);
                    foreach ($tmpValue as $key => $val) {
                        if($key == $label) {
                            if($val != null) {
                                $value = $val;
                            }else{
                                $value = '';
                            }
                        }
                    }
                }
            }
        }
    }
}
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
    <?php
    $class_seo = $seo_detect ? 'seo-detect' : '';
    ?>
    @foreach($langs as $key => $item)
        <input type="text" id="{{ $idName }}{{get_lang_suffix($item)}}"
               data-validation="{{ $validation }}"
               class="form-control {{$class_seo}} {{get_lang_class($key, $item)}} @if(!empty($maxlengthHtml)) has-maxLength @endif  @if(!empty($validation)) has-validation @endif"
               @if (isset($maxlengthHtml)) {!! balanceTags($maxlengthHtml) !!} @endif
               name="{{ $idName }}{{get_lang_suffix($item)}}" value="{{ get_translate($value, $item) }}"
               data-post-id="{{$post_id}}"
               data-seo-detect="{{$seo_put_to}}"
               @if(!empty($item)) data-lang="{{$item}}" @endif>
    @endforeach
</div>
@if($break)
    <div class="w-100"></div> @endif

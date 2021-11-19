<?php
$layout = (!empty($layout)) ? $layout : 'col-12';
if (empty($value)) {
    $value = $std;
}
$idName = str_replace(['[', ']'], '_', $id);

enqueue_script('select2-js');
enqueue_style('select2-css');
$value = explode(',', $value);
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
    <select id="{{ $idName }}"
            class="form-control select2-multiple {{ $style }} @if (!empty($validation)) has-validation @endif"
            data-validation="{{ $validation }}" data-values="{{ json_encode($value) }}"
            data-toggle="select2" name="{{ $id }}[]">
        @if (!empty($choices))
            @if (!is_array($choices))
                <?php
                $choices = explode(':', $choices);
                if ($choices[0] == 'number_range') {
                    $range = explode('_', $choices[1]);
                    $choices = [];
                    for ($i = $range[0]; $i <= $range[1]; $i++) {
                        $choices[$i] = $i;
                    }
                } elseif ($choices[0] == 'hh_currencies') {
                    $choices = list_currencies(true);
                } elseif ($choices[0] == 'taxonomy') {
                    $choices = get_taxonomies();
                } elseif ($choices[0] == 'terms') {
                    $choices = get_terms($choices[1]);
                }
                ?>
            @endif
            @foreach ($choices as $key => $title)
                <option value="{{ $key }}" @if(in_array($key, $value)) selected @endif>{{ $title }}</option>
            @endforeach
        @endif
    </select>
    <div class="clearfix"></div>
</div>
@if($break)
    <div class="w-100"></div> @endif

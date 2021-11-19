<?php
$layout = (!empty($layout)) ? $layout : 'col-12';
if (empty($value)) {
    $value = $std;
}
$idName = str_replace(['[', ']'], '_', $id);

enqueue_script('select2-js');
enqueue_style('select2-css');
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
            class="form-control {{ $style }} @if (!empty($validation)) has-validation @endif"
            data-validation="{{ $validation }}"
            data-toggle="select2" name="{{ $id }}">
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
            @if($choice_group)
                @foreach ($choices as $key => $option)
                    <optgroup label="{{ $key }}">
                        @foreach($option as $_key => $title)
                            <option value="{{ $_key }}" @if($_key == $value) selected @endif>{{ $title }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            @else
                @foreach ($choices as $key => $title)
                    <option value="{{ $key }}" @if($key == $value) selected @endif>{{ $title }}</option>
                @endforeach
            @endif
        @endif
    </select>
    <div class="clearfix"></div>
</div>
@if($break)
    <div class="w-100"></div> @endif

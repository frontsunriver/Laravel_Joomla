<?php

$layout = (!empty($layout)) ? $layout : 'col-12';
if (empty($value)) {
    $value = $std;
}
if (empty($value)) {
    $value = ';;';
}
$value = explode(';', $value);

$fontsData = \ThemeOptions::getGoogleFonts();
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
    </label> <br/>
    <select id="{{ $idName }}" data-toggle="select2"
            class="hh-google-fonts form-control {{ $style }}"
            name="{{ $id }}">
        <option data-variants="" data-subsets="" value="">Select Font</option>
        @foreach ($fontsData as $key => $font)
            <?php
            $variants = implode(',', $font['variants']);
            $subsets = implode(',', $font['subsets']);
            ?>
            <option value="{{ $key }}" @if($key == $value[0]) selected @endif
            data-variants="{{ $variants }}"
                    data-subsets="{{ $subsets }}">{{ $font['name'] }}</option>
        @endforeach
    </select>
    <div class="hh-font-variants clearfix">
        <?php $variants = explode(',', $value[1]); ?>
        @if (!empty($fontsData[$value[0]]['variants']))
            @foreach ($fontsData[$value[0]]['variants'] as $variant)
                @if (in_array($variant, $variants))
                    <div class="checkbox checkbox-success"><input type="checkbox" id="font-variant-{{ $variant }}"
                                                                  name="font_variants[]" value="{{ $variant }}" checked><label
                            for="font-variant-{{ $variant }}">{{ $variant }}</label></div>
                @else
                    <div class="checkbox checkbox-success"><input type="checkbox" id="font-variant-{{ $variant }}"
                                                                  name="font_variants[]" value="{{ $variant }}"><label
                            for="font-variant-{{ $variant }}">{{ $variant }}</label></div>
                @endif
            @endforeach
        @endif
    </div>
    <div class="hh-font-subsets clearfix">
        <?php $subsets = explode(',', $value[2]); ?>
        @if (!empty($fontsData[$value[0]]['subsets']))
            @foreach ($fontsData[$value[0]]['subsets'] as $subset)
                @if (in_array($subset, $subsets))
                    <div class="checkbox checkbox-success"><input type="checkbox" id="font-subset-{{ $subset }}"
                                                                  name="font_subsets[]" value="{{ $subset }}"
                                                                  checked><label
                            for="font-subset-{{ $subset }}">{{ $subset }}</label></div>
                @else
                    <div class="checkbox checkbox-success"><input type="checkbox" id="font-subset-{{ $subset }}"
                                                                  name="font_subsets[]" value="{{ $subset }}"><label
                            for="font-subset-{{ $subset }}">{{ $subset }}</label></div>
                @endif
            @endforeach
        @endif
    </div>
    <div class="clearfix"></div>
</div>
@if($break)
    <div class="w-100"></div> @endif

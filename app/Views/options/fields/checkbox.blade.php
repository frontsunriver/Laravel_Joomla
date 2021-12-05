<?php
$layout = (!empty($layout)) ? $layout : 'col-12';
if (empty($value) && !is_array($value)) {
    $value = $std;
}

$tempValue = $value;
$idName = str_replace(' ', '-', str_replace(['[', ']'], '_', $id));
$value = explode(',', $value);
$langs = $translation == false ? [""] : get_languages_field();

$facilities_check = array();
?>

<div id="setting-{{ $idName }}" data-condition="{{ $condition }}"
     data-unique="{{ $unique }}"
     data-operator="{{ $operation }}"
     class="form-group mb-3 col {{ $layout }} field-{{ $type }}">
    <label for="{{ $idName }}">
        {{ htmlspecialchars_decode($label) }}
        @if (!empty($desc))
            <i class="dripicons-information field-desc" data-toggle="popover" data-placement="right"
               data-trigger="hover"
               data-content="{{ __($desc) }}"></i>
        @endif
    </label><br/>
    <div class="checkbox-wrapper">
        @if (!empty($choices))
            @if (!is_array($choices))
                <?php
                $choicesTemp = explode(':', $choices);
                if ($choicesTemp[0] == 'taxonomy') {
                    $choicesTemp = get_taxonomies();
                } elseif ($choicesTemp[0] == 'terms') {
                    // if($choicesTemp[1] == 'home-facilities'){
                    //     $choicesTemp = get_terms('home-amenity');
                    // }else {
                    //     $choicesTemp = get_terms($choicesTemp[1]);
                    // }
                    if($choicesTemp[1] == 'home-facilities'){
                        if(!empty($tempValue)){
                            $selectedValue = json_decode($tempValue);
                            foreach ($selectedValue as $key => $val) {
                                if($key == $label){
                                    if($val != null){
                                        $facilities_check = $val;
                                    }
                                }
                            }
                        }
                        
                    }
                    $choicesTemp = get_terms($choicesTemp[1]);
                } 
                ?>
            @else
                <?php $choicesTemp = $choices; ?>
            @endif
            @if(!empty($choicesTemp))
                @if ($style == 'col')
                    <div class="row">
                        @endif
                            @if(explode(':', $choices)[1] == 'home-facilities')
                            @if(!empty($selection_val))
                                <?php
                                    $selection_val = json_decode($selection_val);
                                ?>
                                @if(count($selection_val) > 0)
                                    @foreach($selection_val as $index=>$item)
                                        @if ($style == 'col')
                                            <div class="col-12 col-sm-4 col-md-3">
                                                @endif
                                                <?php
                                                    
                                                ?>
                                                <div class="checkbox  checkbox-success @if ($style != 'col') {{$style}} @endif">
                                                    <input type="checkbox"
                                                        id="{{ $idName }}-{{ $item }}"
                                                        value="{{ $item }}"
                                                        @if(in_array($item, $facilities_check)) checked @endif
                                                        name="{{ $idName }}[]">

                                                    <label for="{{ $idName }}-{{ $item }}">
                                                            <span>{{$item}}</span>
                                                    </label>
                                                </div>
                                                @if ($style == 'col')
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                            @else
                                @foreach ($choicesTemp as $key => $title)
                                    @if ($style == 'col')
                                        <div class="col-12 col-sm-4 col-md-3">
                                            @endif
                                            <div class="checkbox  checkbox-success @if ($style != 'col') {{$style}} @endif">
                                                <input type="checkbox"
                                                    name="{{ $idName }}[]"
                                                    value="{{ $key }}"
                                                    @if(in_array($key, $value)) checked @endif
                                                    id="{{ $idName }}-{{ $key }}">

                                                <label for="{{ $idName }}-{{ $key }}">
                                                    @foreach($langs as $key => $item)
                                                        <span class="{{get_lang_class($key, $item)}}"
                                                            @if(!empty($item)) data-lang="{{$item}}" @endif>
                                                        {!! balanceTags(get_translate($title, $item)) !!}
                                                    </span>
                                                    @endforeach
                                                </label>
                                            </div>
                                            @if ($style == 'col')
                                        </div>
                                    @endif
                                @endforeach
                            @endif





                        
                        @if ($style == 'col')
                    </div>
                @endif
            @else
                <small><i>{{__('No data')}}</i></small>
            @endif
        @endif
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif

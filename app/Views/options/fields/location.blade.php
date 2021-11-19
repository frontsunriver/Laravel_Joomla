<?php
    $layout = (!empty($layout)) ? $layout : 'col-12';
    if (empty($value)) {
        $value = $std;
    }
    $value = wp_parse_args((array)$value, [
        'address' => '',
        'city' => '',
        'state' => '',
        'postcode' => '',
        'country' => '',
        'lat' => 48.856613,
        'lng' => 2.352222,
        'zoom' => 13
    ]);
    $idName = str_replace(['[', ']'], '_', $id);

    enqueue_style('mapbox-gl-css');
    enqueue_style('mapbox-gl-geocoder-css');
    enqueue_script('mapbox-gl-js');
    enqueue_script('mapbox-gl-geocoder-js');
    $langs = get_languages_field();
?>
<div id="setting-{{ $idName }}" data-condition="{{ $condition }}"
     data-unique="{{ $unique }}"
     data-operator="{{ $operation }}"
     class="form-group mb-3 col {{ $layout }} field-{{ $type }}">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="{{ $idName }}_address_">{{__('Street Address')}}</label>
                @foreach($langs as $key => $item)
                    <input type="text"
                           class="form-control hh-real-address @if (!empty($validation)) has-validation @endif {{get_lang_class($key, $item)}}"
                           name="{{ $id }}[address]{{!empty($item) ? '['. $item .']' : ''}}"
                           id="{{ $idName }}_address{{get_lang_suffix($item)}}"
                           value="{{ get_translate($value['address'], $item) }}"
                           @if(!empty($item)) data-lang="{{$item}}" @endif>
                @endforeach
            </div>
        </div>
        <div class="w-100 mb-3"></div>
    </div>
    <div class="row">
        <div class="col">
            <div class="mapbox-wrapper">
                <div class="form-group mapbox-text-search">
                    <div class="form-control" data-plugin="mapbox-geocoder" data-value=""
                         data-placeholder="{{__('Add to the map')}}" data-lang="{{get_current_language()}}"></div>
                    <input type="text" class="input-none hh-address"
                           name=""
                           id="{{ $idName }}_search_address" value="">
                </div>

                <div id="{{ $idName }}_mapbox_" class="mapbox-content"
                     data-lat="{{ (float)$value['lat'] }}" data-lng="{{ (float)$value['lng'] }}"
                     data-zoom="{{ $value['zoom'] }}"></div>
                <input type="text" class="input-none hh-zoom" name="{{ $id }}[zoom]"
                       value="{{ $value['zoom'] }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-sm-6">
            <div class="form-group">
                <label for="{{ $idName }}_city_">{{__('City')}}</label>
                @foreach($langs as $key => $item)
                    <input type="text" class="form-control hh-city {{get_lang_class($key, $item)}}"
                           name="{{ $id }}[city]{{!empty($item) ? '['. $item .']' : ''}}"
                           id="{{ $idName }}_city{{get_lang_suffix($item)}}"
                           value="{{ get_translate($value['city'], $item) }}"
                           @if(!empty($item)) data-lang="{{$item}}" @endif>
                @endforeach
            </div>
        </div>
        <div class="col col-sm-6">
            <div class="form-group">
                <label for="{{ $idName }}_state_">{{__('State')}}</label>
                @foreach($langs as $key => $item)
                    <input type="text" class="form-control hh-state {{get_lang_class($key, $item)}}"
                           name="{{ $id }}[state]{{!empty($item) ? '['. $item .']' : ''}}"
                           id="{{ $idName }}_state{{get_lang_suffix($item)}}"
                           value="{{ get_translate($value['state'], $item) }}"
                           @if(!empty($item)) data-lang="{{$item}}" @endif>
                @endforeach
            </div>
        </div>
        <div class="col col-sm-6">
            <div class="form-group">
                <label for="{{ $idName }}_postcode_">{{__('PostCode')}}</label>
                <input type="text" class="form-control hh-postcode" name="{{ $id }}[postcode]"
                       id="{{ $idName }}_postcode_"
                       value="{{ $value['postcode'] }}">
            </div>
        </div>
        <div class="col col-sm-6">
            <div class="form-group">
                <label for="{{ $idName }}_country_">{{__('Country')}}</label>
                @foreach($langs as $key => $item)
                    <input type="text" class="form-control hh-country {{get_lang_class($key, $item)}}"
                           name="{{ $id }}[country]{{!empty($item) ? '['. $item .']' : ''}}"
                           id="{{ $idName }}_country{{get_lang_suffix($item)}}"
                           value="{{ get_translate($value['country'], $item) }}"
                           @if(!empty($item)) data-lang="{{$item}}" @endif>
                @endforeach
            </div>
        </div>
        <div class="col col-sm-6">
            <div class="form-group">
                <label for="{{ $idName }}_lat_">{{__('Latitude')}}</label>
                <input type="text" class="form-control hh-lat" name="{{ $id }}[lat]"
                       id="{{ $idName }}_lat_" value="{{ $value['lat'] }}">
            </div>
        </div>
        <div class="col col-sm-6">
            <div class="form-group">
                <label for="{{ $idName }}_lng_">{{__('Longtitude')}}</label>
                <input type="text" class="form-control hh-lng" name="{{ $id }}[lng]"
                       id="{{ $idName }}_lng_" value="{{ $value['lng'] }}">
            </div>
        </div>
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif

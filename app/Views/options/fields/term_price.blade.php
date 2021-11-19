<?php
$layout = (!empty($layout)) ? $layout : 'col-12';
if (empty($value) && !is_array($value)) {
    $value = $std;
}
$idName = str_replace(['[', ']'], '_', $id);
$langs = $translation == false ? [""] : get_languages_field();
$value = maybe_unserialize($value);
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
    </label><br/>

    <div class="checkbox-wrapper">
        @if (!empty($choices))
            @if (!is_array($choices))
                <?php
                $choices = get_terms($choices, true);
                ?>
            @endif
            @if(!empty($choices))
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>
                                <div class="checkbox checkbox-success hh-check-all">
                                    <input type="checkbox" id="hh-checkbox-all">
                                    <label for="hh-checkbox-all">
                                        <span>{{__('Name')}}</span>
                                    </label>
                                </div>
                            </th>
                            <th>{{__('Base Price')}}</th>
                            <th>{{__('Custom Price')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($choices as $item)
                            <?php
                            $term_id = $item->term_id;
                            $custom_price = '';
                            $choose = 'no';
                            if (isset($value[$term_id])) {
                                $choose = $value[$term_id]['choose'];
                                if (!empty($value[$term_id]['custom'])) {
                                    $custom_price = $value[$term_id]['price'];
                                }
                            }
                            ?>
                            <tr>
                                <th scope="row" class="align-middle">
                                    <div class="checkbox  checkbox-success ">
                                        <input type="checkbox" @if($choose == 'yes') checked
                                               @endif name="{{$idName}}[id][{{$term_id}}]" value="{{$term_id}}"
                                               id="car_equipment-{{$term_id}}" class="hh-check-all-item">
                                        <label for="car_equipment-{{$term_id}}">
                                            @foreach($langs as $key => $val)
                                                <span class="{{get_lang_class($key, $val)}}"
                                                      @if(!empty($val)) data-lang="{{$val}}" @endif>
                                                {!! balanceTags(get_translate($item->term_title, $val)) !!}
                                            </span>
                                            @endforeach
                                        </label>
                                    </div>
                                </th>
                                <td class="align-middle">{{convert_price($item->term_price)}}</td>
                                <td class="align-middle">
                                    <input type="text" value="{{$custom_price}}" name="{{$idName}}[price][{{$term_id}}]"
                                           class="form-control p-1 w-50">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                {{__('No Data')}}
            @endif
        @endif
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif

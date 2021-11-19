<?php
global $post;

$layout = (!empty($layout)) ? $layout : 'col-12';
if (empty($value) && !is_array($value)) {
    $value = $std;
}
$value = (!empty($value) && is_array($value)) ? $value : [];

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
    <div class="row row-enable-price-categories">
        <div class="col">
            <div class="checkbox-wrapper">
                <div class="checkbox checkbox-success">
                    <input type="checkbox" name="{{ $id }}[]" value="enable_adults"
                           @if(in_array('enable_adults', $value) || empty($value)) checked @endif
                           id="{{ $idName }}-enable_adults">
                    <label for="{{ $idName }}-enable_adults">{{__('Adults')}}</label>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="checkbox-wrapper">
                <div class="checkbox checkbox-success">
                    <input type="checkbox" name="{{ $id }}[]" value="enable_children"
                           @if(in_array('enable_children', $value)) checked @endif
                           id="{{ $idName }}-enable_children">
                    <label for="{{ $idName }}-enable_children">{{__('Children')}}</label>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="checkbox-wrapper">
                <div class="checkbox checkbox-success">
                    <input type="checkbox" name="{{ $id }}[]" value="enable_infants"
                           @if(in_array('enable_infants', $value)) checked @endif
                           id="{{ $idName }}-enable_infants">
                    <label for="{{ $idName }}-enable_infants">{{__('Infants')}}</label>
                </div>
            </div>
        </div>
    </div>
    <?php
    $price_primary = $post->price_primary;
    ?>
    <table class="table table-borderless table-price-categories mt-3">
        <thead class="thead-light">
        <tr>
            <th class="align-middle">{{ __('Type') }}</th>
            <th class="align-middle" style="width: 50%">{{ __('Price') }}</th>
            <th class="align-middle text-center">{{ __('Primary') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr id="setting-{{ $idName }}-price-adult" class="form-group form-sm"
            data-condition="{{$id}}:is(enable_adults)">
            <td class="align-middle">
                <label for="{{ $idName }}-price-adult">{{ __('Adults') }}</label>
            </td>
            <td class="align-middle">
                <input id="{{ $idName }}-price-adult" type="text" class="form-control" name="adult_price"
                       value="{{ $post->adult_price }}">
            </td>
            <td class="align-middle text-center">
                <div class="checkbox-wrapper">
                    <div class="radio radio-success">
                        <input id="{{$idName}}-price-type-primary-adult" type="radio" name="price_primary"
                               @if(empty($price_primary) || $price_primary == 'adult_price') checked @endif
                               value="adult_price">
                        <label for="{{$idName}}-price-type-primary-adult">&nbsp;</label>
                    </div>
                </div>
            </td>
        </tr>
        <tr id="setting-{{ $idName }}-price-child" class="form-group form-sm"
            data-condition="{{$id}}:is(enable_children)">
            <td class="align-middle">
                <label for="{{ $idName }}-price-child">{{ __('Children') }}</label>
            </td>
            <td class="align-middle">
                <input id="{{ $idName }}-price-child" type="text" class="form-control" name="child_price"
                       value="{{ $post->child_price }}">
            </td>
            <td class="align-middle text-center">
                <div class="checkbox-wrapper">
                    <div class="radio radio-success">
                        <input id="{{$idName}}-price-type-primary-child" type="radio" name="price_primary"
                               @if($price_primary == 'child_price') checked @endif
                               value="child_price">
                        <label for="{{$idName}}-price-type-primary-child">&nbsp;</label>
                    </div>
                </div>
            </td>
        </tr>
        <tr id="setting-{{ $idName }}-price-infant" class="form-group form-sm"
            data-condition="{{$id}}:is(enable_infants)">
            <td class="align-middle">
                <label for="{{ $idName }}-price-infant">{{ __('Infants') }}</label>
            </td>
            <td class="align-middle">
                <input id="{{ $idName }}-price-infant" type="text" class="form-control" name="infant_price"
                       value="{{ $post->infant_price }}">
            </td>
            <td class="align-middle text-center">
                <div class="checkbox-wrapper">
                    <div class="radio radio-success">
                        <input id="{{$idName}}-price-type-primary-infant" type="radio" name="price_primary"
                               @if($price_primary == 'infant_price') checked @endif
                               value="infant_price">
                        <label for="{{$idName}}-price-type-primary-infant">&nbsp;</label>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
@if($break)
    <div class="w-100"></div> @endif

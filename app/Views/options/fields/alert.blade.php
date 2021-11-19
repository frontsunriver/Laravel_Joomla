<?php
if ($value === '') {
    $value = $std;
}
$idName = str_replace(['[', ']'], '_', $id);
?>
<div id="setting-{{ $idName }}" data-condition="{{ $condition }}"
     data-unique="{{ $unique }}"
     data-operator="{{ $operation }}"
     data-hh-bind-value-from="{{$bind_value_from}}"
     class="form-group mb-2 col {{ $layout }} field-{{ $type }}">
    <div class="alert alert-{{$style}}" role="alert">
        @if(!empty($label))
            <h5 class="title">{{$label}}</h5>
        @endif
        <p class="f12">{!! balanceTags($desc) !!}</p>
    </div>
</div>
@if($break)
    <div class="w-100"></div> @endif

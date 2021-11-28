<?php
show_lang_section('mb-2');
$langs = get_languages_field();
?>
<div class="form-group">
    <label for="term_name_update">
        {{__('Name')}}
    </label>
    @foreach($langs as $key => $item)
        <input type="text" class="form-control has-validation {{get_lang_class($key, $item)}}"
               data-validation="required" id="term_name_update{{get_lang_suffix($item)}}"
               name="term_name{{get_lang_suffix($item)}}"
               value="{{ get_translate($termObject->term_title, $item) }}" @if(!empty($item)) data-lang="{{$item}}"
               @endif
               placeholder="Name">
    @endforeach
    <input type="hidden" name="term_id" value="{{ $termObject->term_id }}">
    <input type="hidden" name="term_type" value="home-advanced">
    <input type="hidden" name="term_encrypt" value="{{ hh_encrypt($termObject->term_id) }}">
</div>
<div class="form-group field-icon ">
    <label for="term_icon">
        {{__('Home Facilities')}}
    </label>
    <?php
    $term_select = json_decode($termObject->term_select);
    $facilities_list = get_terms('home-facilities'); ?>
    <div class="item-filter-wrapper" id="home-facilities">
        <?php foreach ($facilities_list as $key => $value) { 
            $checked = '';
            ?>
                <div class="label">{{ $value['title'] }}</div>
                <?php $idName = str_replace(' ', '-', str_replace(['[', ']'], '_', $value['title']));?>
                <div class="content">
                    <div class="row">
                        <?php $sub_val = json_decode($value['selection_val']);
                            if(!empty($sub_val)){
                                foreach ($sub_val as $item) { 
                                    $checked = '';
                                    if(!empty($term_select)) {
                                        foreach ($term_select as $k=>$v) {
                                            if($k == $value['title']){
                                                foreach ($v as $t) {
                                                    if($t == $item) {
                                                        $checked = 'checked';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="col-lg-4 mb-1">
                                        <div class="item checkbox  checkbox-success ">
                                            <input type="checkbox" value="{{$item}}" onchange="checkFacility('update')" {{$checked}}
                                                id="{{$idName}}_{{$item}}_1"/>
                                            <label
                                                for="{{$idName}}_{{$item}}_1">{{ $item }}</label>
                                        </div>
                                    </div>
                                <?php }
                            }
                        ?>
                    </div>
                </div>
        <?php } ?>
        <input type="hidden" id="facility_val_1" name="term_select"/>
    </div>
</div>

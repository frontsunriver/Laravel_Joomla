<?php
show_lang_section('mb-2');
$langs = get_languages_field();
?>
<div class="form-group">
    <label for="term_name_update">
        {{__('Field Name')}}
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
    <input type="hidden" name="term_encrypt" value="{{ hh_encrypt($termObject->term_id) }}">
</div>
<div class="form-group mb-5">
    <label for="term_sub_update">
        {{__('Value name')}}
    </label>
    
    <?php 
        $current = 1;
        $sub_val = array();
        if(!empty($termObject->term_select)) {
            $val = json_decode($termObject->term_select,true);
            $current = count($val);
            if($current == 0) {
                $current = 1;
            }
            $sub_val = $val;
        }
    ?>
    <div id="subnameGroup_update">
        <input type="hidden" placeholer="Value name" id="currentNum_update" name="currentNum_update" value="<?=$current?>">
        @if(empty($sub_val))
            <input type="text" class="form-control hh-icon-input mb-3 has-validation has-translation"
            id="sub_name_update_1" name="sub_name_update_1"
            placeholder="">
        @else
            @foreach($sub_val as $key=>$value)
                <input type="text" class="form-control hh-icon-input mb-3 has-validation has-translation"
                    id="sub_name_update_<?=$key + 1?>" name="sub_name_update_<?=$key + 1?>" value="<?=$value?>"
                    placeholder="">
            @endforeach
        @endif
    </div>
    
    
    <a href="javascript:updateSubname();" class="btn btn-info float-right mb-3">Add Value</a>
</div>
<div class="form-group field-icon ">
    <label for="term_icon_update">
        {{__('Icon')}}
    </label>
    <?php
    $icon = $termObject->term_icon;
    ?>
    <input type="text" class="form-control hh-icon-input"
           id="term_icon_update" name="term_icon"
           data-action="{{ dashboard_url('get-font-icon') }}"
           data-plugin="fonticon" value="{{ $icon }}"
           placeholder="{{__('Icon')}}">
</div>

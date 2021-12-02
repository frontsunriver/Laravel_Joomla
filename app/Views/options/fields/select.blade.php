<?php
$layout = (!empty($layout)) ? $layout : 'col-12';
if (empty($value)) {
    $value = $std;
}
$idName = str_replace(['[', ']'], '_', $id);

enqueue_script('nice-select-js');
enqueue_style('nice-select-css');
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
    <select id="{{ $idName }}"
            class="{{ $style }} @if(!empty($validation)) has-validation @endif"
            data-plugin="customselect"
            data-validation="{{ $validation }}"
            name="{{ $id }}">
        <?php
        if($id != 'author') {
            if (!empty($choices)){
                if (!is_array($choices)) {
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
                    } elseif ($choices[0] == 'page') {
                        $pages = get_all_posts('page');
                        $choices = [];
                        if ($pages['total'] > 0) {
                            foreach ($pages['results'] as $item) {
                                $choices[$item->post_id] = $item->post_title;
                            }
                        }
                    } elseif ($choices[0] == 'user') {
                        $for_option = true;
                        if (isset($choices[2])) {
                            $for_option = $choices[2];
                        }
                        $choices = get_users_by_role($choices[1], $for_option);
                    } elseif ($choices[0] == 'nav') {
                        $choices = get_navigation();
                    } elseif ($choices[0] == 'language') {
                        $langs = config('locales.languages');
                        array_unshift($langs, __('Select language'));
                        $choices = $langs;
                    } elseif ($choices[0] == 'post_type') {
                        $choices = get_posttypes(true);
                    }elseif($choices[0] == 'list'){
                        if (isset($choices[1]) && $choices[1] == 'hour') {
                            $choices = list_hours($choices[2]);
                        }
                    }
                }
                if(is_array($choices)){
                    foreach ($choices as $key => $title){
                    if (empty($title)) {
                        $title = __('Default - 0');
                    }
                    ?>
                    <option value="{{ $key }}" @if($key == $value) selected @endif>{{ __(get_translate($title)) }}</option>
                    <?php
                    }
                }
            }
        }else {
            $allUsers = get_all_user_list();
            foreach ($allUsers as $val) { ?>
                <option value="{{$val->id}}" @if($val->id == $value) selected @endif>{{$val->email}}</option>
            <?php }
        }
        ?>
    </select>
    <div class="clearfix"></div>
</div>
@if($break)
    <div class="w-100"></div> @endif

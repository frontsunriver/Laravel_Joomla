<?php
$layout = (!empty($layout)) ? $layout : 'col-12';
if (empty($value)) {
    $value = $std;
} else {
    if (isset($compare_std_list_item) && $compare_std_list_item && !empty($std)) {
        if (strpos($std, 'callback__') !== FALSE) {
            $explode_std = explode('callback__', $std);
            $func = isset($explode_std[1]) ? $explode_std[1] : '';
            if (function_exists($func)) {
                $default_value = $func();
                if (!empty($default_value) && count($value) !== count($default_value)) {
                    $value_tmp = [];
                    foreach ($default_value as $item) {
                        $same_value = false;
                        foreach ($value as $_item) {
                            if ($item['id'] == $_item['id']) {
                                $same_value = true;
                                break;
                            }
                        }
                        if ($same_value) {
                            $value_tmp[] = $_item;
                        } else {
                            $value_tmp[] = $item;
                        }
                    }
                    if (!empty($value_tmp)) {
                        $value = $value_tmp;
                    }
                }
            }
        }
    }
}
if (is_string($value) && strpos($value, 'callback__') !== FALSE) {
    $value = explode('callback__', $value);
    $func = $value[1] ?? '';
    if (!empty($func) && function_exists($func)) {
        $value = $func();
    }
}

$value = maybe_unserialize($value);
$tmp_items = base64_encode(maybe_serialize($items));
$idName = str_replace(['[', ']'], '_', $id);

if (!empty($enqueue_scripts)) {
    foreach ($enqueue_scripts as $script) {
        enqueue_script($script);
    }
}

if (!empty($enqueue_styles)) {
    foreach ($enqueue_styles as $style) {
        enqueue_style($style);
    }
}

if (isset($condition) && !empty($condition)) {
    $conditions = explode(':', $condition);
    if (isset($conditions[0]) && $conditions[0] == 'settings' && count($conditions) == 3) {
        $setting = get_option($conditions[1]);
        if ($setting != $conditions[2]) {
            $layout .= ' hh-hidden-field';
        }
    }
}
?>

<div id="setting-{{ $id }}" data-condition="{{ $condition }}"
     data-unique="{{ $unique }}"
     data-operator="{{ $operation }}" data-bind-from="{{ $bind_from }}"
     class="form-group mb-3 col {{ $layout }} field-{{ $type }}"
     data-id="{{ $id }}" data-items="{{ $tmp_items }}">
    <label for="{{ $idName }}">
        {{ __($label) }}
        @if (!empty($desc))
            <i class="dripicons-information field-desc" data-toggle="popover" data-placement="right"
               data-trigger="hover"
               data-content="{{ __($desc) }}"></i>
        @endif
    </label>
    <ul class="hh-render hh-list-items">
        @if (!empty($value))
            <?php
            $currentOptions = [];
            ?>
            @foreach ($value as $key => $val)
                <li class="hh-list-item">
                <span class="hh-list-item-heading">
                        <span class="htext"></span>
                        @if(!isset($editable_list_item) || (isset($editable_list_item) && $editable_list_item))
                        <a href="javascript: void(0)" class="edit"><i class="ti-minus"></i></a>
                        <a href="javascript: void(0)" class="close"><i class="ti-close"></i></a>
                    @else
                        <a href="javascript: void(0)" class="edit"><i class="fe-more-vertical"></i></a>
                    @endif
                    </span>
                    <div class="render">
                        <?php
                        $unique = time() . rand(0, 9999);
                        foreach ($items as $item) {
                            $item['unique'] = $unique;
                            if (isset($val[$item['id']])) {
                                $item['value'] = $val[$item['id']];
                            }

                            $item['id'] = $id . '[' . $item['id'] . '][]' . $unique;
                            $item = \ThemeOptions::mergeField($item);
                            echo \ThemeOptions::loadField($item);
                        }
                        ?>
                    </div>
                </li>
            @endforeach
        @endif
    </ul>
    @if(!isset($button_add_new_list_item) || (isset($button_add_new_list_item) && $button_add_new_list_item))
        <a href="javascript:void(0)" class="btn btn-success add-list-item"
           data-url="{{ \ThemeOptions::url('get-list-item') }}"><i class="icon-plus"></i></a>
    @endif
</div>
@if($break)
    <div class="w-100"></div> @endif

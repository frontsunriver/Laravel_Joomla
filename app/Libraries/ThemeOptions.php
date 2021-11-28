<?php

use App\Models\Option;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Home;
use App\Models\Page;
use App\Models\Post;

class ThemeOptions
{
    private static $optionID = 'hh_theme_options';
    private static $optionValues = null;
    private static $fieldTranslate = ['text', 'textarea', 'upload', 'editor'];

    public static function getOptionID()
    {
        return self::$optionID;
    }

    public static function getOptionsConfig()
    {
        return apply_filters('hh_theme_options_config', Config::get('awebooking.theme_options'));
    }

    public static function getListItem($request)
    {

        $items = request()->get('items', '');
        $id = request()->get('id', '');
        $html = '';
        if ($items && $id) {
            $items = maybe_unserialize(base64_decode($items));
            $defaultField = self::getDefaultField();
            $html = '<li class="hh-list-item"><span class="hh-list-item-heading"><span class="htext"></span><a href="javascript: void(0)" class="edit"><i class="ti-minus"></i></a><a href="javascript: void(0)" class="close"><i class="ti-close"></i></a></span><div class="render">';
            $unique = time() . rand(0, 9999);
            foreach ($items as $item) {
                $item['unique'] = $unique;
                $item['id'] = $id . '[' . $item['id'] . '][]' . $unique;
                $item = wp_parse_args($item, $defaultField);
                $html .= self::loadField($item, true);
            }
            $html .= '</div></li>';
        }
        return response()->json([
            'status' => 1,
            'html' => $html
        ]);
    }

    public static function getGoogleFonts()
    {
        $fonts = [];
        $key = get_option('google_font_key', 'AIzaSyDPG7nZZoCpIP9wsi5S3AvavW4Jtvs1UxY');
        $url = "https://www.googleapis.com/webfonts/v1/webfonts?key={$key}";
        try {
            $responsive = file_get_contents($url);
            $fontsData = json_decode($responsive);
            if (isset($fontsData->items)) {
                if (!empty($fontsData) && is_object($fontsData)) {
                    foreach ($fontsData->items as $font) {
                        $fonts[Str::slug($font->family)] = [
                            'name' => $font->family,
                            'variants' => $font->variants,
                            'subsets' => $font->subsets
                        ];
                    }
                }
            }
        } catch (Exception $e) {

        }

        return $fonts;
    }

    public static function url($url = '')
    {
        return url(Config::get('awebooking.prefix_dashboard') . '/' . $url);
    }

    public static function applyData($serviceID, $field, $meta)
    {
        $field['post_id'] = $serviceID;
        if (is_object($meta)) {
            $meta = (array)$meta;
        }
        $field['value'] = (isset($meta[$field['id']])) ? maybe_unserialize($meta[$field['id']]) : '';

        if ($field['type'] == 'location') {
            $prefix = $field['id'] . '_';
            $field['value'] = [
                'address' => $meta[$prefix . 'address'],
                'city' => $meta[$prefix . 'city'],
                'state' => $meta[$prefix . 'state'],
                'postcode' => $meta[$prefix . 'postcode'],
                'country' => $meta[$prefix . 'country'],
                'lat' => $meta[$prefix . 'lat'],
                'lng' => $meta[$prefix . 'lng'],
                'zoom' => $meta[$prefix . 'zoom']
            ];
        }
        if (isset($field['choices']) && !empty($field['choices'] && is_string($field['choices']))) {
            $choices = explode(':', $field['choices']);
            if (is_array($choices) && $choices[0] === 'terms') {
                $value = [];
                if($choices[1] == 'home-facilities'){
                    $field['value'] = $meta['facilities'];
                } else if($choices[1] == 'home-distance'){
                    $field['value'] = $meta['distance'];
                } else {
                    $terms = get_the_terms($serviceID, $choices[1]);
                    if (!is_null($terms)) {
                        foreach ($terms as $term) {
                            $value[] = $term->term_id;
                        }
                    }
                    $field['value'] = implode(',', $value);
                }
            }
        }
        if ($field['type'] == 'term_price') {
            $field['value'] = maybe_unserialize($field['value']);
        }
        return $field;
    }

    public static function renderOption()
    {
        return view('options.option');
    }

    public static function renderMeta($key = '', $serviceObject = null, $addNew = true, $action = '', $redirectTo = '')
    {
        return view('options.meta', ['serviceObject' => $serviceObject, 'key' => $key, 'addNew' => $addNew, 'action' => $action, 'redirectTo' => $redirectTo]);
    }

    public static function renderPageMeta($key = '', $newPage = null, $addNew = true, $redirectTo = '', $service = 'page')
    {
        return view('options.page-meta', ['newPage' => $newPage, 'key' => $key, 'addNew' => $addNew, 'redirectTo' => $redirectTo, 'service' => $service]);
    }

    public static function loadField($field, $return = false)
    {
        $role = get_user_role(get_current_user_id(), 'slug');
        if (View::exists('options.fields.' . $field['type']) && in_array($role, $field['permission'])) {
            // if(!in_array('selection_val', $field)) {
            //     $filed['selection_val'] = '';
            // }
            // print_r($field);
            return view('options.fields.' . $field['type'], $field)->render();
        }
        return '';
    }

    public static function fetchField($field)
    {

        if ($field['field_type'] == 'meta') {
            if ($field['type'] == 'list_item') {
                $value = request()->get($field['id'], '');
                $return = [];

                if (!empty($value) && is_array($value)) {
                    $count_arr = [];
                    foreach ($value as $val_item) {
                        $count_arr[] = count($val_item);
                    }

                    if (!empty($count_arr)) {
                        $count_item = max($count_arr);
                        $langs = get_languages();
                        $count_lang = count($langs);

                        if (isset($field['translation']) && $field['translation'] && $count_lang > 0) {
                            $count_item = $count_item / $count_lang;
                        }

                        for ($i = 0; $i < $count_item; $i++) {
                            $item = [];
                            foreach ($field['items'] as $key => $val) {
                                if (isset($val['translation']) && $val['translation'] && $count_lang > 0) {
                                    $trans_val = '';
                                    foreach ($langs as $klang => $vlang) {
                                        $val_temp = (isset($value[$val['id']]) && isset($value[$val['id']][(count($langs) * $i + $klang)])) ? $value[$val['id']][(count($langs) * $i + $klang)] : '';
                                        $trans_val .= '[:' . $vlang . ']' . $val_temp;
                                    }
                                    $trans_val .= '[:]';
                                    $item[$val['id']] = $trans_val;
                                } else {
                                    $item[$val['id']] = (isset($value[$val['id']]) && isset($value[$val['id']][$i])) ? $value[$val['id']][$i] : '';
                                }
                            }
                            $return[] = $item;
                        }
                    }
                }
                return serialize($return);
            } elseif ($field['type'] == 'checkbox' || $field['type'] == 'select2_multiple' || $field['type'] == 'price_categories') {
                $value = (array)request()->get($field['id'], '');
                $value = implode(',', $value);
                return $value;
            } elseif ($field['type'] == 'google_font') {
                $font = request()->get($field['id'], '');
                $font_variants = request()->get('font_variants', '');
                $font_subsets = request()->get('font_subsets', '');
                if (!empty($font)) {
                    $value = $font;
                    if (!empty($font_variants)) {
                        $value .= ';' . implode(',', $font_variants);
                    } else {
                        $value .= ';';
                    }
                    if (!empty($font_subsets)) {
                        $value .= ';' . implode(',', $font_subsets);
                    } else {
                        $value .= ';';
                    }
                } else {
                    $value = ';;';
                }
                return $value;
            } elseif ($field['type'] == 'on_off') {
                return request()->get($field['id'], 'off');
            } elseif ($field['type'] == 'permalink') {
                return Str::slug(request()->get($field['id']));
            } else {
                if (isset($field['translation']) && $field['translation'] && in_array($field['type'], self::$fieldTranslate)) {
                    $value = set_translate($field['id']);
                } else {
                    $value = request()->get($field['id'], '');
                }
                return $value;
            }
        } else {
            if ($field['type'] == 'location') {
                $return = [];
                $value = request()->get($field['id'], '');
                if (!empty($value['address']) && is_array($value['address'])) {
                    $return['postcode'] = $value['postcode'];
                    $return['lat'] = $value['lat'];
                    $return['lng'] = $value['lng'];
                    $return['zoom'] = $value['zoom'];

                    $need_filter = ['address', 'city', 'state', 'country'];
                    foreach ($need_filter as $item) {
                        $val_temp = '';
                        foreach ($value[$item] as $key => $val) {
                            $val_temp .= '[:' . $key . ']' . $val;
                        }
                        $val_temp .= '[:]';
                        $return[$item] = $val_temp;
                    }
                    return $return;
                } else {
                    return $value;
                }
            } elseif ($field['type'] == 'term_price') {
                $termObject = get_terms($field['choices'], true);
                $termData = [];
                if (!empty($termObject)) {
                    foreach ($termObject as $term) {
                        $termData[$term->term_id] = [
                            'title' => $term->term_title,
                            'price' => $term->term_price
                        ];
                    }
                }

                $value = request()->get($field['id'], '');
                $return = [];
                if (!empty($value['price'])) {
                    foreach ($value['price'] as $key => $val) {
                        $status = isset($value['id'][$key]) ? 'yes' : 'no';
                        if (!empty($val)) {
                            $price = (float)$val;
                        } else {
                            $price = $termData[$key]['price'];
                        }
                        $custom = empty($val) ? false : true;
                        $return[$key] = [
                            'choose' => $status,
                            'price' => $price,
                            'custom' => $custom
                        ];
                    }
                }
                return serialize($return);
            } else {
                if (isset($field['translation']) && $field['translation'] && in_array($field['type'], self::$fieldTranslate)) {
                    $value = set_translate($field['id']);
                } else {
                    $value = request()->get($field['id'], '');
                }
            }
            return $value;
        }
    }

    public static function getOption($key, $default = '', $in_setting = false)
    {
        if (!is_null(self::$optionValues)) {
            $options = self::$optionValues;
        } else {
            $option = new Option();
            $options = $option->getOption(self::getOptionID());
            self::$optionValues = $options;
        }

        $current_options = [];

        if (!empty($options)) {
            $current_options = unserialize($options->option_value);
        }

        if (isset($current_options[$key])) {
            $value = maybe_unserialize($current_options[$key]);
            if (empty($value)) {
                return $default;
            }
            if (is_string($value) && !$in_setting) {
                if (strpos($value, '[:]') !== FALSE) {
                    return get_translate($value);
                } else {
                    return $value;
                }

            } else {
                return $value;
            }
        }
        return $default;
    }

    public static function filterFields($options)
    {
        $return = [];
        foreach ($options['fields'] as $field) {
            if ($field['type'] == 'tab') {
                foreach ($field['tab_content'] as $content) {
                    $return[] = $content;
                }
            } else {
                $return[] = $field;
            }
        }

        return $return;
    }

    public static function mergeField($field)
    {
        $default = self::getDefaultField();
        foreach ($default as $key => $value) {
            if (isset($field[$key])) {
                $default[$key] = $field[$key];
            }
        }

        return $default;
    }

    public static function getDefaultField()
    {
        return [
            'id' => '',
            'type' => '',
            'label' => '',
            'desc' => '',
            'std' => '',// can use 'callback__{function_name} to get std value - list item
            'value' => '',
            'choices' => [], // array, taxonomy:{taxonomy_name}, page, number_range:0:100
            'choice_group' => false,
            'items' => [], //only for list item
            'button_add_new_list_item' => true, // on/off button add new - list item
            'editable_list_item' => true, // on/off delete button in list item
            'compare_std_list_item' => false,// Merge value with 'std' - list item
            'bind_from' => '',
            'bind_value_from' => '',
            'field_type' => 'meta', //taxonomy
            'layout' => 'col-12',
            'style' => '', // 'wide', 'form-check-inline', 'col', 'danger' -  used for select, checkbox, radio, alert
            'tab_title' => [],
            'tab_content' => [],
            'post_id' => '',
            'break' => false,
            'maxlength' => false, /* 'maxlength' => [ 'max-length' => 20, 'always-show' => true, 'threshold' => 10 ],  */
            'validation' => '', // bootstrap validation js: min, max, required, email, matches, ...
            'condition' => '',
            'minlength' => 0,
            'min_date' => date('Y-m-d'), // or '-1' if not set. Used for datapicker
            'operation' => 'and',
            'unique' => '',
            'escape' => false,
            'section' => '',
            'post_type' => '',
            'excluded' => false,
            'enqueue_scripts' => [], // used for list_item
            'enqueue_styles' => [], // used for list_item,
            'permission' => ['administrator', 'partner', 'customer', 'superadmin'],// ['administrator', 'partner']
            'translation' => false,
            'seo_detect' => false,
            'seo_put_to' => '',
            'selection_val' => '',
        ];
    }

    public static function get_inst()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }

}

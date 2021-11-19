<?php
/**
 * Created by PhpStorm.
 * Date: 2/5/2020
 * Time: 5:02 PM
 */

use App\Models\Language;
use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

function is_rtl()
{
    global $hh_rtl;
    return $hh_rtl;
}

function get_current_language()
{
    return app()->getLocale();
}

function show_lang_section($class = '')
{
    $langs = get_languages(true);
    if (count($langs) > 0) {
        ?>
        <div id="hh-language-action" class="hh-language-action <?php echo esc_attr($class) ?>">
            <ul>
                <?php foreach ($langs as $k => $lang) { ?>
                    <li>
                        <a href="javascript:void(0);" class="item <?php echo esc_attr($k == 0 ? 'active' : '') ?>"
                           data-code="<?php echo esc_attr($lang['code']); ?>">
                            <img
                                src="<?php echo esc_attr(asset('vendor/countries/flag/32x32/' . $lang['flag_code'] . '.png')) ?>"/>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <?php
    }
}

function is_multi_language()
{
    global $hh_is_multi_language;

    if (is_null($hh_is_multi_language)) {
        $hh_is_multi_language = get_option('multi_language', 'off');
    }

    if ($hh_is_multi_language == 'on') {
        return true;
    } else {
        return false;
    }
}

function get_lang_class($key, $item)
{
    $class = [];
    if ($key > 0) {
        array_push($class, 'hidden');
    }
    if (!empty($item)) {
        array_push($class, 'has-translation');
    }
    if (!empty($class)) {
        return ' ' . implode(' ', $class);
    }
    return '';
}

function get_lang_attribute($item)
{
    if (!empty($item)) {
        return 'data-lang="' . $item . '"';
    }
    return '';
}

function get_lang_suffix($code)
{
    if (!empty($code)) {
        return '_' . $code;
    }
    return '';
}

function get_languages_field()
{
    global $hh_available_languages;

    return empty($hh_available_languages) ? [""]: $hh_available_languages;
}

function get_languages($full = false)
{
    if (is_multi_language()) {
        $langs = Language::where('status', 'on')->orderBy('priority', 'ASC')->get();
        if (!$full) {
            $codes = [];
            if (!$langs->isEmpty()) {
                foreach ($langs as $item) {
                    array_push($codes, $item->code);
                }
            }
            return $codes;
        }
        return $langs;
    }
    return [];
}

function get_main_language()
{
    return get_option('site_language', config('app.locale'));
}

function get_current_language_data()
{
    $langs = get_languages(true);
    $current_lang_code = get_current_language();
    foreach ($langs as $key => $lang) {
        if ($current_lang_code == $lang['code']) {
            return $lang;
        }
    }
    return [];
}

function awe_lang($text)
{
    return $text;
}

function get_translate($text, $lang = '', $format = false)
{
    $text_convert = $text;

    if (strpos($text, '[:]') !== FALSE) {
        if (empty($lang)) {
            $lang = get_current_language();
        }

        $first_lang_string = '[:' . trim($lang) . ']';
        $last_lang_string = '[:';

        $text_convert = get_string_between($text, $first_lang_string, $last_lang_string);
        if (!$text_convert) {
            $lang = get_main_language();
            $first_lang_string = '[:' . trim($lang) . ']';
            $text_convert = get_string_between($text, $first_lang_string, $last_lang_string);
        }
    }

    return $text_convert;
}

function set_translate($field_name = '')
{

    $text = '';
    if (is_multi_language()) {
        $langs = get_languages();
        if (!empty($langs)) {
            foreach ($langs as $key => $code) {
                $input_name = $field_name . '_' . $code;
                if (isset($_POST[$input_name])) {
                    $text .= '[:' . $code . ']' . request()->get($input_name, '');
                } else {
                    $text .= '[:' . $code . ']' . request()->get($field_name, '');
                }
            }
            $text .= '[:]';
        } else {
            $text = request()->get($field_name, '');
        }
    } else {
        $text = request()->get($field_name, '');
    }

    return $text;
}

function get_string_between($string, $start, $end)
{
    $ini = strpos($string, $start);
    if ($ini === FALSE) {
        return false;
    };
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

<?php

use App\Controllers\MailController;
use App\Controllers\Services\HomeController;
use App\Models\Car;
use App\Models\Comment;
use App\Models\Experience;
use App\Models\Home;
use App\Models\Media;
use App\Models\Option;
use App\Models\Page;
use App\Models\Post;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

function parse_request($request, $keys)
{
    $return = [];
    foreach ($keys as $key) {
        $return[$key] = $request->get($key);
    }

    return $return;
}

function get_timezone()
{
    return get_option('timezone', 'Europe/London');
}

function null2empty($text)
{
    return is_null($text) ? '' : $text;
}

function detect_link(string $value, int $img = 1, int $video = 1, array $protocols = array('http', 'mail', 'twitter', 'https'), array $attributes = array('target' => '_blank'), $video_height = 400)
{
    $links = array();
    $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) {
        return '<' . array_push($links, $match[1]) . '>';
    }, $value);
    foreach ((array)$protocols as $protocol) {
        switch ($protocol) {
            case 'http':
            case 'https':
                $value = preg_replace_callback('~(?:\(?(https?)://([^\s\!]+)(?<![?,:.\"]))~i',
                    function ($match) use ($protocol, &$links, $attributes, $img, $video, $video_height) {
                        if ($match[1]) {

                            $protocol = $match[1];
                            $str = $match[0];
                            if ($str[0] === '(') {
                                $match[2] = substr($match[2], 0, -1);
                            }
                            $link = $match[2] ?: $match[3];
                            if ($video) {
                                if (strpos($link, 'youtube.com') !== false || strpos($link, 'youtu.be') !== false) {
                                    $exp = explode('=', $link);
                                    $ht = '<iframe width="100%" height="' . $video_height . '" src="https://www.youtube.com/embed/' . end($exp) . '?rel=0&showinfo=0&color=orange&iv_load_policy=3" frameborder="0" allowfullscreen></iframe>';
                                    return '<' . array_push($links, $ht) . '></a>';
                                }
                                if (strpos($link, 'vimeo.com') !== false) {
                                    $exp = explode('/', $link);
                                    $ht = '<iframe width="100%" height="' . $video_height . '" src="https://player.vimeo.com/video/' . end($exp) . '" frameborder="0" allowfullscreen></iframe>';
                                    return '<' . array_push($links, $ht) . '></a>';
                                }
                            }
                            if ($img) {
                                if (strpos($link, '.png') !== false || strpos($link, '.jpg') !== false || strpos($link, '.jpeg') !== false || strpos($link, '.gif') !== false || strpos($link, '.bmp') !== false || strpos($link, '.webp') !== false) {
                                    return '<' . array_push($links, "<a target='_blank' href=\"$protocol://$link\" class=\"htmllink\"><img alt=\"" . __('Attachment') . "\" src=\"$protocol://$link\" class=\"htmlimg\">") . '></a>';
                                }
                            }

                            if ($str[0] === '(') {
                                return '<' . array_push($links, "(<a target='_blank' href=\"$protocol://$link\" class=\"htmllink\">$link</a>)") . '>';
                            } else {
                                return '<' . array_push($links, "<a target='_blank' href=\"$protocol://$link\" class=\"htmllink\">$link</a>") . '>';
                            }
                        }
                    }, $value);
                break;
            case 'mail':
                $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attributes) {
                    return '<' . array_push($links, "<a target='_blank' href=\"mailto:{$match[1]}\" class=\"htmllink\">{$match[1]}</a>") . '>';
                }, $value);
                break;
            case 'twitter':
                $value = preg_replace_callback('~(?<!\w)[@#]([\w\._]+)~', function ($match) use (&$links, $attributes) {
                    return '<' . array_push($links, "<a target='_blank' href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1] . "\" class=\"htmllink\">{$match[0]}</a>") . '>';
                }, $value);
                break;
            default:
                $value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attributes) {
                    return '<' . array_push($links, "<a target='_blank' href=\"$protocol://{$match[1]}\" class=\"htmllink\">{$match[1]}</a>") . '>';
                }, $value);
                break;
        }
    }
    return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) {
        return $links[$match[1] - 1];
    }, $value);
}

function get_awebooking_info()
{
    $info = get_file_data(base_path('awebooking.php'));
    $default = [
        'Name' => '',
        'Description' => '',
        'Version' => '1.0',
        'Author' => '',
    ];
    $info = wp_parse_args($info, $default);

    return $info;
}

function get_file_data($file)
{
    $default_headers = ['Name', 'Slug', 'Description', 'Author', 'Version', 'Tags'];
    // We don't need to write to the file, so just open for reading.
    $fp = fopen($file, 'r');

    // Pull only the first 8 KB of the file in.
    $file_data = fread($fp, 8 * 1024);

    // PHP will close file handle, but we are good citizens.
    fclose($fp);

    // Make sure we catch CR-only line endings.
    $file_data = str_replace("\r", "\n", $file_data);

    /**
     * Filters extra file headers by context.
     *
     * The dynamic portion of the hook name, `$context`, refers to
     * the context where extra headers might be loaded.
     *
     * @param array $extra_context_headers Empty array by default.
     * @since 2.9.0
     *
     */

    $all_headers = $default_headers;
    $return = [];
    foreach ($all_headers as $field => $regex) {
        if (preg_match('/^[ \t\/*#@]*' . preg_quote($regex, '/') . ':(.*)$/mi', $file_data, $match) && $match[1]) {
            $return[$regex] = trim(preg_replace('/\s*(?:\*\/|\?>).*/', '', $match[1]));
        } else {
            $return[$regex] = '';
        }
    }

    return $return;
}

/* Check an url is available in time range */
function verify_time($time, $in_minutes = 5)
{
    $now = time();
    $diff = hh_date_diff($time, $now, 'minute');
    return !!($diff <= $in_minutes);
}

function rmdir_recursive($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
                    rmdir_recursive($dir . DIRECTORY_SEPARATOR . $object);
                else
                    unlink($dir . DIRECTORY_SEPARATOR . $object);
            }
        }
        rmdir($dir);
    }
}

function glob_recursive($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
        $files = array_merge($files, glob_recursive($dir . '/' . basename($pattern), $flags));
    }
    return $files;
}

function is_dashboard()
{
    $route = Route::current();
    $prefix = $route->getPrefix();

    return !!str_replace('/', '', $prefix) == Config::get('awebooking.prefix_dashboard');
}


function get_gender_name($gender_id)
{
    $genders = Config::get('awebooking.gender');
    return isset($genders[$gender_id]) ? $genders[$gender_id] : '';
}

function get_ical_url($post_id, $post_type = 'home')
{
    $post_info = post_type_info($post_type);
    return url($post_info['slug'] . '/ical/' . $post_id . '/ical.ics');
}

function get_origin_filter_price($filter_price)
{
    $min = 0;
    $max = 0;

    $primary_currency = current_currency();
    if (!empty($primary_currency) && isset($primary_currency['exchange'])) {
        $exchange_rate = (float)$primary_currency['exchange'];
    } else {
        $exchange_rate = 1;
    }

    $min_max = explode(';', $filter_price);
    if (isset($min_max[0])) {
        $min = floatval($min_max[0]);
    }
    if (isset($min_max[1])) {
        $max = floatval($min_max[1]);
    }

    $min = $min / 1;
    $max = $max / 1;

    return [
        'min' => $min,
        'max' => $max
    ];
}

function get_enabled_service_keys()
{
    $enabled_services = get_posttypes(true);
    if (!empty($enabled_services)) {
        $enabled_services = array_keys($enabled_services);
        return $enabled_services;
    }

    return [];
}

function get_posttypes($option = false, $both = false, $force_enable = false)
{
    $services = [];
    $post_types = Config::get('awebooking.post_types');
    foreach ($post_types as $key => $val) {
        if (!$both && ($key == 'post' || $key == 'page')) {
            continue;
        }
        if ($force_enable) {
            $enable = 'on';
        } else {
            $enable = get_option('enable_' . $key, 'on');
        }

        if ($enable == 'on') {
            if ($option) {
                $services[$key] = $val['name'];
            } else {
                $services[$key] = $val;
            }
        }
    }

    return $services;
}

function is_enable_service($service = 'home')
{
    $enable = get_option('enable_' . $service, 'on');
    if ($enable == 'on') {
        return true;
    }
    return false;
}

function list_tabs_service()
{
    $services = [];
    $post_types = Config::get('awebooking.post_types');
    foreach ($post_types as $key => $val) {
        if ($key == 'post' || $key == 'page') {
            continue;
        }
        $enable = get_option('enable_' . $key, 'on');
        if ($enable == 'on') {
            $services[] = [
                'id' => $key,
                'label' => __($val['name']),
                'only_search_form' => $val['only_search_form']
            ];
        }
    }
    return apply_filters('hh_list_services', $services);
}

function convert_tab_service_to_list_item(): array
{
    return list_tabs_service();
}

function get_video_embed_url($url)
{
    //This is a general function for generating an embed link of an FB/Vimeo/Youtube Video.
    $finalUrl = '';
    if (strpos($url, 'facebook.com/') !== false) {
        //it is FB video
        $finalUrl .= 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($url) . '&show_text=1&width=200';
    } else if (strpos($url, 'vimeo.com/') !== false) {
        //it is Vimeo video
        $videoId = explode("vimeo.com/", $url)[1];
        if (strpos($videoId, '&') !== false) {
            $videoId = explode("&", $videoId)[0];
        }
        $finalUrl .= 'https://player.vimeo.com/video/' . $videoId;
    } else if (strpos($url, 'youtube.com/') !== false) {
        //it is Youtube video
        $videoId = explode("v=", $url)[1];
        if (strpos($videoId, '&') !== false) {
            $videoId = explode("&", $videoId)[0];
        }
        $finalUrl .= 'https://www.youtube.com/embed/' . $videoId;
    } else if (strpos($url, 'youtu.be/') !== false) {
        //it is Youtube video
        $videoId = explode("youtu.be/", $url)[1];
        if (strpos($videoId, '&') !== false) {
            $videoId = explode("&", $videoId)[0];
        }
        $finalUrl .= 'https://www.youtube.com/embed/' . $videoId;
    }

    if (!empty($finalUrl)) {
        return '<iframe width="560" height="315" src="' . $finalUrl . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    }
    return '';
}

function get_dashboard_folder()
{
    $folder = 'customer';
    if (Sentinel::inRole('administrator')) {
        $folder = 'administrator';
    } elseif (Sentinel::inRole('partner')) {
        $folder = 'partner';
    } elseif (Sentinel::inRole('superadmin')) {
        $folder = 'superadmin';
    }

    return $folder;
}

function list_hours($step = 30)
{
    $start_time = new \DateTime('2010-01-01 00:00');
    $end_time = new \DateTime('2010-01-01 23:59');
    $time_array = array();

    $time_format = hh_time_format();

    while ($start_time <= $end_time) {
        $time = $start_time->format($time_format);
        $time_key = $time;
        $str_start = substr($time, 0, 2);
        $str_end = substr($time, strlen($time) - 2, 2);
        if ($str_start == '12' && in_array($str_end, ['AM', 'am'])) {
            $time = '00' . substr($time, 2);
        }
        $time_array[$time_key] = $time;
        $start_time->add(new \DateInterval('PT' . $step . 'M'));
    }

    return $time_array;
}

function referer_field($echo = true)
{
    $referer_field = '<input type="hidden" name="_hh_http_referer" value="' . esc_attr(stripslashes($_SERVER['REQUEST_URI'])) . '" />';

    if ($echo) {
        echo balanceTags($referer_field);
    }
    return $referer_field;
}

function get_referer($default = '')
{

    $referer = request()->get('_hh_http_referer', $default);
    return $referer;
}

function need_approve_review()
{
    $enable_review = get_option('review_approval');
    if ($enable_review == 'on') {
        return true;
    }
    return false;
}

function user_can_review($user_id, $post_id, $type = 'home')
{
    return \App\Controllers\CommentController::get_inst()->userCanReview($user_id, $post_id, $type);
}

function enable_review()
{
    $enable_review = get_option('enable_review', 'on');
    if ($enable_review == 'on') {
        return true;
    }
    return false;
}

function get_search_page($post_type = 'home', $params = '')
{
    if (empty($post_type)) {
        $post_type = 'home';
    }
    $func = 'get_' . $post_type . '_search_page';
    return $func($params);
}

function get_preview_permalink($type = 'home', $post_id = '')
{
    $post_type_info = post_type_info($type);
    $url = url($post_type_info['slug']);
    if (!empty($post_id)) {
        $url .= '/' . $post_id;
    }

    return $url;
}

function get_permalink_by_id($post_id, $post_type = 'post')
{
    $link = '';
    switch ($post_type) {
        case 'page':
            $model = new Page();
            break;
        case 'home':
            $model = new Home();
            break;
        case 'experience':
            $model = new Experience();
            break;
        case 'post':
        default:
            $model = new Post();
            break;
    }
    $postObject = $model->getById($post_id);
    if (!is_null($postObject)) {
        $link = get_the_permalink($post_id, $postObject->post_slug, $post_type);
    }
    return $link;
}

function get_menu_dashboard()
{
    if (Sentinel::inRole('administrator')) {
        $menu = Config::get('awebooking.admin_menu');
    } elseif (Sentinel::inRole('partner')) {
        $menu = Config::get('awebooking.partner_menu');
    } else if(Sentinel::inRole('superadmin')){
        $menu = Config::get('awebooking.superadmin_menu');
    }

    return $menu;

}

function updateEnv($key = 'APP_KEY', $key_value = '')
{
    $path = base_path('.env');
    if (file_exists($path)) {
        file_put_contents($path, str_replace(
            $key . '=' . env($key), $key . '=' . $key_value, file_get_contents($path)
        ));
    }
}

function setEnvironmentValue(array $values)
{
    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);
    if (!empty($str)) {
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $str .= "\n"; // In case the searched variable is in the last line without \n
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }
            }
        }

        $str = substr($str, 0, -1);
        if (!file_put_contents($envFile, $str)) return false;
        return true;
    }
    return false;
}

function updateConfig($key = '', $key_value = '')
{
    $path = config_path('app.php');
    if (file_exists($path)) {
        file_put_contents($path, str_replace(
            "'" . $key . "' => '" . config('app.' . $key) . "'", "'" . $key . "' => '" . $key_value . "'", file_get_contents($path)
        ));
    }
}

function send_mail($email_from = '', $from_label = '', $email_to, $subject, $body, $email_reply = '')
{
    $mail = new MailController();
    if (empty($email_from)) {
        $admin = get_admin_user();
        $email_from = $admin->email;
    }
    if (empty($from_label)) {
        $from_label = get_option('email_from');
    }
    $mail->setEmailFrom($email_from, $from_label);
    $mail->setEmailTo($email_to);
    if (!empty($email_reply)) {
        $mail->setReplyTo($email_reply);
    }

    return $mail->sendMail($subject, $body);
}

function comment_status_info($name = '')
{
    $status = [
        'publish' => [
            'name' => __('Publish')
        ],
        'pending' => [
            'name' => __('Pending')
        ]
    ];

    if (!empty($name) && isset($status[$name])) {
        return $status[$name];
    } else {
        return $status;
    }
}

function review_rating_star($rate)
{
    if (!empty($rate)) {
        echo '<div class="star-rating">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rate) {
                echo '<i class="fa fa-star"></i>';
            } else {
                echo '<i class="fa fa-star star-none"></i>';
            }
        }
        echo '</div>';
    }
}

function get_blog_image_url()
{
    $image_id = get_option('blog_image');
    return get_attachment_url($image_id);
}


function short_content($text, $num_words = 55, $more = null)
{
    if (null === $more) {
        $more = '&hellip;';
    }

    $original_text = $text;
    $text = strip_all_tags($text);

    $words_array = preg_split("/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY);
    $sep = ' ';
    if (count($words_array) > $num_words) {
        array_pop($words_array);
        $text = implode($sep, $words_array);
        $text = $text . $more;
    } else {
        $text = implode($sep, $words_array);
    }

    return apply_filters('trim_words', $text, $num_words, $more, $original_text);
}

function strip_all_tags($string, $remove_breaks = false)
{
    $string = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);
    $string = strip_tags($string);

    if ($remove_breaks) {
        $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
    }

    return trim($string);
}

function balanceTags($text, $force = false, $detect_link = false)
{
    $text = str_replace('<script>', '&lt;script&gt;', $text);
    $text = str_replace('</script>', '&lt;/script&gt;', $text);
    return $detect_link ? detect_link(force_balance_tags($text)) : force_balance_tags($text);
}

function force_balance_tags($text)
{
    $tagstack = array();
    $stacksize = 0;
    $tagqueue = '';
    $newtext = '';
    // Known single-entity/self-closing tags
    $single_tags = array('area', 'base', 'basefont', 'br', 'col', 'command', 'embed', 'frame', 'hr', 'img', 'input', 'isindex', 'link', 'meta', 'param', 'source');
    // Tags that can be immediately nested within themselves
    $nestable_tags = array('blockquote', 'div', 'object', 'q', 'span');

    // WP bug fix for comments - in case you REALLY meant to type '< !--'
    $text = str_replace('< !--', '<    !--', $text);
    // WP bug fix for LOVE <3 (and other situations with '<' before a number)
    $text = preg_replace('#<([0-9]{1})#', '&lt;$1', $text);

    while (preg_match('/<(\/?[\w:]*)\s*([^>]*)>/', $text, $regex)) {
        $newtext .= $tagqueue;

        $i = strpos($text, $regex[0]);
        $l = strlen($regex[0]);

        // clear the shifter
        $tagqueue = '';
        // Pop or Push
        if (isset($regex[1][0]) && '/' == $regex[1][0]) { // End Tag
            $tag = strtolower(substr($regex[1], 1));
            // if too many closing tags
            if ($stacksize <= 0) {
                $tag = '';
                // or close to be safe $tag = '/' . $tag;

                // if stacktop value = tag close value then pop
            } elseif ($tagstack[$stacksize - 1] == $tag) { // found closing tag
                $tag = '</' . $tag . '>'; // Close Tag
                // Pop
                array_pop($tagstack);
                $stacksize--;
            } else { // closing tag not at top, search for it
                for ($j = $stacksize - 1; $j >= 0; $j--) {
                    if ($tagstack[$j] == $tag) {
                        // add tag to tagqueue
                        for ($k = $stacksize - 1; $k >= $j; $k--) {
                            $tagqueue .= '</' . array_pop($tagstack) . '>';
                            $stacksize--;
                        }
                        break;
                    }
                }
                $tag = '';
            }
        } else { // Begin Tag
            $tag = strtolower($regex[1]);

            // Tag Cleaning

            // If it's an empty tag "< >", do nothing
            if ('' == $tag) {
                // do nothing
            } elseif (substr($regex[2], -1) == '/') { // ElseIf it presents itself as a self-closing tag...
                // ...but it isn't a known single-entity self-closing tag, then don't let it be treated as such and
                // immediately close it with a closing tag (the tag will encapsulate no text as a result)
                if (!in_array($tag, $single_tags)) {
                    $regex[2] = trim(substr($regex[2], 0, -1)) . "></$tag";
                }
            } elseif (in_array($tag, $single_tags)) { // ElseIf it's a known single-entity tag but it doesn't close itself, do so
                $regex[2] .= '/';
            } else { // Else it's not a single-entity tag
                // If the top of the stack is the same as the tag we want to push, close previous tag
                if ($stacksize > 0 && !in_array($tag, $nestable_tags) && $tagstack[$stacksize - 1] == $tag) {
                    $tagqueue = '</' . array_pop($tagstack) . '>';
                    $stacksize--;
                }
                $stacksize = array_push($tagstack, $tag);
            }

            // Attributes
            $attributes = $regex[2];
            if (!empty($attributes) && $attributes[0] != '>') {
                $attributes = ' ' . $attributes;
            }

            $tag = '<' . $tag . $attributes . '>';
            //If already queuing a close tag, then put this tag on, too
            if (!empty($tagqueue)) {
                $tagqueue .= $tag;
                $tag = '';
            }
        }
        $newtext .= substr($text, 0, $i) . $tag;
        $text = substr($text, $i + $l);
    }

    // Clear Tag Queue
    $newtext .= $tagqueue;

    // Add Remaining text
    $newtext .= $text;

    // Empty Stack
    while ($x = array_pop($tagstack)) {
        $newtext .= '</' . $x . '>'; // Add remaining tags to close
    }

    // WP fix for the bug with HTML comments
    $newtext = str_replace('< !--', '<!--', $newtext);
    $newtext = str_replace('<    !--', '< !--', $newtext);

    return $newtext;
}

function wp_redirect($location, $status = 302, $x_redirect_by = 'Laravel')
{

    if (!$location) {
        return false;
    }

    if (is_string($x_redirect_by)) {
        header("X-Redirect-By: $x_redirect_by");
    }

    header("Location: $location", true, $status);

    return true;
}

function get_short_address($item)
{
    $html = '';
    if (get_translate($item->location_city)) {
        $html .= get_translate($item->location_city) . ', ';
    }
    if (get_translate($item->location_country)) {
        $html .= get_translate($item->location_country);
    }

    if (empty($html)) {
        $html = get_translate($item->location_address);
    }

    return $html;
}

function _n($string = '', $var = 0)
{
    $string = __($string);
    $split_string = explode('][', $string);
    if (is_array($split_string)) {
        $count = sizeof($split_string);
        foreach ($split_string as $key => $text) {
            if ($key === $count - 1) {
                $text = substr($text, 0, -1);
            }
            if ($key === 0) {
                $text = substr($text, 1);
            }

            $split_text = explode("::", $text);
            if ($key === $count - 1 && $var >= $split_text[0]) {
                return str_replace('%s', $var, $split_text[1]);
            } else {
                if ($split_text[0] == $var) {
                    return str_replace('%s', $var, $split_text[1]);
                }
            }
        }
    }
    return $string;
}

function sitemap_per_page()
{
    $sitemap_per_page = get_opt('sitemap_per_page', '10');
    return $sitemap_per_page;
}

function posts_per_page()
{
    $posts_per_page = Config::get('awebooking.posts_per_page');
    return $posts_per_page;
}

function comments_per_page($type = 'blog')
{
    $comment_per_page_conf = Config::get('awebooking.comments_per_page');
    if (isset($comment_per_page_conf[$type])) {
        return $comment_per_page_conf[$type];
    } else {
        return 5;
    }
}

function booking_status_info($name = '')
{
    $booking_status = Config::get('awebooking.booking_status');
    if (empty($name)) {
        return isset($booking_status) ? $booking_status : false;
    }
    return isset($booking_status[$name]) ? $booking_status[$name] : false;
}

function payout_status_info($name = '')
{
    $payout_status = Config::get('awebooking.payout_status');
    if (empty($name)) {
        return isset($payout_status) ? $payout_status : false;
    }
    return isset($payout_status[$name]) ? $payout_status[$name] : false;
}

function get_payments($key = '')
{
    $allPayments = apply_filters('hh_payment_gateways', Config::get('awebooking.payment_gateways'));
    if ($key) {
        return (isset($allPayments[$key])) ? $allPayments[$key] : false;
    }
    return (!empty($allPayments)) ? $allPayments : false;
}

function get_available_payments($payment = '')
{
    $allPayments = get_payments($payment);
    if (!$allPayments) {
        return false;
    }
    if (is_array($allPayments)) {
        $return = [];
        foreach ($allPayments as $key => $_payment) {
            $enable = get_option('enable_' . $key, 'off');
            if ($enable == 'on') {
                $return[] = $_payment;
            }
        }

        return $return;
    } else {
        $enable = get_option('enable_' . $payment, 'off');
        return ($enable == 'on') ? $allPayments : false;
    }
}

function get_payment_options($type = 'title')
{
    $allPayments = get_payments();
    $return = [];
    foreach ($allPayments as $key => $payment) {
        $return['title'][] = $payment::getOptions()['title'];
        foreach ($payment::getOptions()['content'] as $item) {
            $return['content'][] = $item;
        }
    }
    if (!empty($type)) {
        return $return[$type];
    }
    return $return;
}

function get_edit_link()
{
    global $post;
    $current_route = Route::current();
    $params = $current_route->parameters();
    if (isset($params['home_name'])) {
        return dashboard_url('edit-home', $post->post_id);
    }
    if (isset($params['experience_name'])) {
        return dashboard_url('edit-experience', $post->post_id);
    }
    if (isset($params['car_name'])) {
        return dashboard_url('edit-car', $post->post_id);
    }
    if (isset($params['post_slug'])) {
        return dashboard_url('edit-post', $post->post_id);
    }
    if (isset($params['page_slug'])) {
        return dashboard_url('edit-page', $post->post_id);
    }

    return false;
}

function get_site_name()
{
    return get_option('site_name', Config::get('app.name', __('Laravel App')));
}

function get_site_description()
{
    return get_option('site_description', __('Awesome Booking System'));
}

function page_title($is_dashboard = false)
{
    $title = get_site_name() . '-' . get_site_description();
    $current_route = Route::current();
    $name = $current_route->getName();
    $params = $current_route->parameters();
    foreach ($params as $key => $param) {
        if ($key !== 'page' && $key !== 'id') {
            $name .= '/' . $param;
        }
    }
    if ($is_dashboard) {
        $menu = Config::get('awebooking.customer_menu');
        if (is_admin()) {
            $menu = Config::get('awebooking.admin_menu');
        } elseif (is_partner()) {
            $menu = Config::get('awebooking.partner_menu');
        }
        foreach ($menu as $item) {
            if ($item['type'] == 'item' || $item['type'] == 'hidden') {
                if ($item['screen'] === $name) {
                    $title = $item['label'] . ' - ' . $title;
                    break;
                }
            } elseif ($item['type'] == 'parent') {
                foreach ($item['child'] as $sub_item) {
                    if ($sub_item['screen'] === $name) {
                        $title = $sub_item['label'] . ' - ' . $title;
                        break;
                    }
                }
            }
        }
    } else {
        $params = $current_route->parameters();
        global $post;
        if (isset($params['home_id'])) {
            $title = get_translate($post->post_title) . ' - ' . $title;
        } elseif (isset($params['experience_id'])) {
            $title = get_translate($post->post_title) . ' - ' . $title;
        } elseif (isset($params['car_id'])) {
            $title = get_translate($post->post_title) . ' - ' . $title;
        } elseif (isset($params['post_id'])) {
            $title = get_translate($post->post_title) . ' - ' . $title;
        } elseif (isset($params['page_id'])) {
            $title = get_translate($post->post_title) . ' - ' . $title;
        } else {
            $name = $current_route->getName();
            $pages_name = Config::get('awebooking.pages_name');
            foreach ($pages_name as $item) {
                if ($item['screen'] === $name) {
                    $title = __($item['label']) . ' - ' . $title;
                }
            }
        }
        $title = apply_filters('awebooking_post_title', $title, $params, $post);
    }


    return apply_filters('awebooking_page_title', $title, $is_dashboard, $name);
}

function is_singular()
{
    $current_route = Route::current();
    $params = $current_route->parameters();
    if (isset($params['home_id']) || isset($params['experience_id']) || isset($params['car_id']) || isset($params['post_id']) || isset($params['page_id'])) {
        return true;
    }
    return false;
}

function dashboard_url($url = '', $id = '', $page = '')
{
    if (empty($id) && empty($page)) {
        return url(Config::get('awebooking.prefix_dashboard') . '/' . $url);
    } else {
        if (!empty($id) && !empty($page)) {
            return url(Config::get('awebooking.prefix_dashboard') . '/' . $url . '/' . $id . '/' . $page);
        } elseif (!empty($id)) {
            return url(Config::get('awebooking.prefix_dashboard') . '/' . $url . '/' . $id);
        } elseif (!empty($page)) {
            return url(Config::get('awebooking.prefix_dashboard') . '/' . $url . '/' . $page);
        }
    }
}

function auth_url($name = '')
{
    return url(Config::get('awebooking.prefix_auth') . '/' . $name);
}

function checkout_url()
{
    return url(Config::get('awebooking.checkout_slug'));
}

function thankyou_url()
{
    return url(Config::get('awebooking.after_checkout_slug'));
}

function current_url()
{
    return Request::fullUrl();
}

function plugin_path($path = '')
{
    $base_path = base_path('plugins');
    return $base_path . ($path ? '/' . $path : '');
}

function current_screen()
{
    return Route::currentRouteName();
}

function start_get_view()
{
    ob_start();
}

function end_get_view()
{
    return @ob_get_clean();
}

function post_type_info($name = '')
{
    $post_types = Config::get('awebooking.post_types');
    if (empty($name)) {
        return isset($post_types) ? $post_types : false;
    }
    return isset($post_types[$name]) ? $post_types[$name] : false;
}

function service_status_info($name = '')
{
    $service_status = Config::get('awebooking.service_status');
    if (empty($name)) {
        return isset($service_status) ? $service_status : false;
    }
    return isset($service_status[$name]) ? $service_status[$name] : false;
}

function dashboard_pagination($args = [])
{
    $defaults = [
        'range' => 4,
        'total' => 0,
        'previous_string' => '<i class="icon-arrow-left"></i>',
        'next_string' => '<i class="icon-arrow-right"></i>',
        'before_output' => '<nav aria-label="navigation"><ul class="pagination">',
        'after_output' => '</ul></nav>',
        'posts_per_page' => posts_per_page(),
    ];

    $args = wp_parse_args($args, $defaults);
    $args['range'] = (int)$args['range'] - 1;
    $posts_per_page = $args['posts_per_page'];

    $count = ceil($args['total'] / $posts_per_page);

    $current_params = \Illuminate\Support\Facades\Route::current()->parameters();
    $page = isset($current_params['page']) ? $current_params['page'] : 1;
    $ceil = ceil($args['range'] / 2);
    if ($count <= 1)
        return false;
    if (!$page)
        $page = 1;
    if ($count > $args['range']) {
        if ($page <= $args['range']) {
            $min = 1;
            $max = $args['range'] + 1;
        } elseif ($page >= ($count - $ceil)) {
            $min = $count - $args['range'];
            $max = $count;
        } elseif ($page >= $args['range'] && $page < ($count - $ceil)) {
            $min = $page - $ceil;
            $max = $page + $ceil;
        }
    } else {
        $min = 1;
        $max = $count;
    }
    $echo = '';
    $url = dashboard_url(Route::currentRouteName());
    foreach ($current_params as $key => $param) {
        if ($key !== 'page') {
            $url .= '/' . $param;
        }
    }
    $previous_num = intval($page) - 1;


    $previous = $url . '/' . $previous_num . '/';

    if ($previous && (1 == $page)) {
        $echo .= '<li class="disabled"><a class="page-link" href="javascript:void(0);" title="previous">' . $args['previous_string'] . '</a></li>';
    }
    if ($previous && (1 != $page)) {
        $echo .= '<li class="page-item"><a class="page-link" data-pagination="' . $previous_num . '" href="' . $previous . '" title="previous">' . $args['previous_string'] . '</a></li>';
    }
    if (!empty($min) && !empty($max)) {
        for ($i = $min; $i <= $max; $i++) {
            if ($page == $i) {
                $echo .= '<li class="active page-item"><a class="page-link" data-pagination="' . $i . '" href="javascript:void(0);">' . str_pad((int)$i, 1, '0', STR_PAD_LEFT) . '</a></li>';
            } else {
                $_url = $url . '/' . $i . '/';
                $echo .= sprintf('<li class="page-item"><a class="page-link" data-pagination="' . $i . '" href="%s">%2d</a></li>', $_url, $i);
            }
        }
    }
    $next_num = intval($page) + 1;

    $next = $url . '/' . $next_num . '/';

    if ($next && ($count == $page)) {
        $echo .= '<li class="disabled"><a class="page-link" href="javascript:void(0);" title="next">' . $args['next_string'] . '</a></li>';
    }
    if ($next && ($count != $page)) {
        $echo .= '<li class="page-item"><a class="page-link" data-pagination="' . $next_num . '" href="' . $next . '" title="next">' . $args['next_string'] . '</a></li>';
    }
    if (isset($echo))
        echo balanceTags($args['before_output'] . $echo . $args['after_output']);
}

function star_rating_render($rate = 0)
{
    $html = '<div class="hh-rating">';
    for ($i = 1; $i <= $rate; $i++) {
        if ($rate >= $i) {
            $html .= '<i class="fas fa-star"></i>';
        } else {
            $html .= '<i class="fas fa-star no-rate"></i>';
        }
    }
    $html .= '</div>';

    return $html;
}

function get_all_posts($post_type = 'post', $number = '-1', $status = ['publish'])
{
    switch ($post_type) {
        case 'page':
            $page = new Page();
            $res = $page->getAllPages([
                'number' => $number,
                'status' => $status
            ]);
            break;
        case 'car':
            $car = new Car;
            $res = $car->getAllCars([
                'number' => $number,
                'status' => $status
            ]);
            break;
        case 'experience':
            $experience = new Experience();
            $res = $experience->getAllExperiences([
                'number' => $number,
                'status' => $status
            ]);
            break;
        case 'home':
            $home = new Home();
            $res = $home->getAllHomes([
                'number' => $number,
                'status' => $status
            ]);
            break;
        default:
            $post = new Post();
            $res = $post->getAllPosts([
                'number' => $number,
                'status' => $status
            ]);
            break;
    }
    return $res;
}


function setup_post_data($post, $type = 'home')
{
    $controller_name = 'App\\Controllers\\Services\\' . ucfirst($type) . 'Controller';
    $post = $controller_name::get_inst()->setup_post_data($post);

    return $post;
}

function get_post($post_id, $post_type = 'home', $global = false)
{
    switch ($post_type) {
        case 'home':
        default:
            $post = HomeController::get_inst()->getById($post_id, $global);
            break;
        case 'experience':
            $post = \App\Controllers\Services\ExperienceController::get_inst()->getById($post_id, $global);
            break;
        case 'car':
            $post = \App\Controllers\Services\CarController::get_inst()->getById($post_id, $global);
            break;
        case 'page':
            $post = \App\Controllers\PageController::get_inst()->getById($post_id, $global);
            break;
        case 'post':
            $post = \App\Controllers\PostController::get_inst()->getById($post_id, $global);
            break;
    }

    return $post;
}

function set_post_thumbnail($post_id, $thumbnail_id, $type = 'home')
{
    $model_name = 'App\\Models\\' . ucfirst($type);
    $model = new $model_name();
    $data = [
        'thumbnail_id' => $thumbnail_id
    ];
    $method_name = 'update' . ucfirst($type);
    return $model->$method_name($data, $post_id);
}

function get_the_permalink($post_id, $post_slug = '', $type = 'home')
{
    if (empty($post_slug)) {
        $modelName = 'App\\Models\\' . ucfirst($type);
        $model = new $modelName();
        $post = $model->getById($post_id);
        if (!is_null($post)) {
            $post_slug = $post->post_slug;
        }
    }
    if (!empty($post_slug)) {
        $post_info = post_type_info($type);
        return url($post_info['slug'] . '/' . $post_id . '/' . $post_slug);
    }
    return url('/');
}

function get_attachment_info($attachment_id, $size = 'full', $default = true)
{
    $attachment = get_attachment($attachment_id);
    if ($attachment) {
        return [
            'id' => $attachment->media_id,
            'url' => get_attachment_url($attachment, $size, $default),
            'description' => $attachment->media_description,
            'author' => $attachment->author,
            'type' => $attachment->media_type,
        ];
    }
    return null;
}

function get_attachment_url($attachment_id, $size = 'full', $default = true)
{
    if (is_object($attachment_id)) {
        $attachment = $attachment_id;
    } else {
        $attachment = get_attachment($attachment_id);
    }

    if (is_object($attachment)) {
        $media_path = base_path($attachment->media_path);
        if (file_exists($media_path)) {
            $media_url = url($attachment->media_url);
            if (\App::environment('production_ssl')) {
                $media_url = str_replace('http:', 'https:', $media_url);
            }
            if ($size == 'full' || $attachment->media_type === 'svg') {
                return $media_url;
            }

            $url_info = pathinfo($media_url);
            $url = $url_info['dirname'];

            $path_info = pathinfo($media_path);
            $name = $path_info['filename'];
            $extension = $attachment->media_type;
            $path = $path_info['dirname'];

            switch ($size) {
                case 'medium':
                    $file = $path . '/' . $name . '-800x600' . '.' . $extension;
                    break;
                case 'small':
                    $file = $path . '/' . $name . '-400x300' . '.' . $extension;
                    break;
                default:
                    $file = $path . '/' . $name . '-' . $size[0] . 'x' . $size[1] . '.' . $extension;
                    break;
            }
            if (file_exists($file)) {
                return $url . '/' . basename($file);
            } else {
                if (in_array($extension, crop_image_types())) {
                    crop_image($media_path, $size);
                    if (is_file($file)) {
                        return $url . '/' . basename($file);
                    } else {
                        return placeholder_image($size);
                    }
                } else {
                    return placeholder_image($size);
                }
            }
        } else {
            if ($default) {
                return placeholder_image($size);
            } else {
                return '';
            }
        }
    } else {
        if ($default) {
            return placeholder_image($size);
        } else {
            return '';
        }
    }

}

function get_file_name(object $attachment, $short = false)
{
    return $short ? Str::limit($attachment->media_name, '10') . '.' . get_file_extension($attachment) : $attachment->media_name . '.' . get_file_extension($attachment);
}

function get_file_extension(object $attachment)
{
    $type = 'png';
    switch ($attachment->media_type) {
        case 'png':
            $type = 'png';
            break;
        case 'jpg':
        case 'jpeg':
            $type = 'jpg';
            break;
        case 'gif':
            $type = 'gif';
            break;
        case 'svg':
            $type = 'svg';
            break;
    }
    return $type;
}

function placeholder_image($size = 'full')
{
    switch ($size) { //https://dummyimage.com/600x400/e0e0e0/fa7399.png
        case 'full':
            $url = 'https://dummyimage.com/1200x900/e0e0e0/c7c7c7.png';
            break;
        case 'medium':
            $url = 'https://dummyimage.com/800x600/e0e0e0/c7c7c7.png';
            break;
        case 'small':
            $url = 'https://dummyimage.com/400x300/e0e0e0/c7c7c7.png';
            break;
        default:
            $url = 'https://dummyimage.com/' . $size[0] . 'x' . $size[1] . '/e0e0e0/c7c7c7.png';
            break;
    }
    return $url;
}

function get_file_size($size = 0)
{
    if ($size >= 1073741824) {
        $size = number_format($size / 1073741824, 2) . 'GB';
    } elseif ($size >= 1048576) {
        $size = number_format($size / 1048576, 2) . 'MB';
    } elseif ($size >= 1024) {
        $size = number_format($size / 1024, 2) . 'KB';
    } elseif ($size > 1) {
        $size = $size . 'bytes';
    } elseif ($size == 1) {
        $size = $size . 'byte';
    } else {
        $size = '0bytes';
    }

    return $size;
}

function crop_image_types()
{
    return ['jpg', 'jpeg', 'png', 'gif', 'webp'];
}

function crop_image($path, $size = [150, 150])
{
    switch ($size) {
        case 'full':
            $size = [1200, 900];
            break;
        case 'medium':
            $size = [800, 600];
            break;
        case 'small':
            $size = [400, 300];
            break;
    }
    try {
        $image = new \Gumlet\ImageResize($path);
        $image->crop($size[0], $size[1]);
        $path_info = pathinfo($path);
        $name = $path_info['filename'] . '-' . $size[0] . 'x' . $size[1];
        $newpath = $path_info['dirname'] . '/' . $name . '.' . $path_info['extension'];
        $image->save($newpath);

        update_image_sizes($size);

        return $newpath;
    } catch (Exception $ex) {
        return false;
    }

}

function update_image_sizes($size)
{
    $size = $size[0] . '-' . $size[1];

    $image_sizes = get_opt('awbooking_image_sizes', []);
    if (!in_array($size, $image_sizes)) {
        $image_sizes[] = $size;
    }
    update_opt('awbooking_image_sizes', $image_sizes);
}

function crop_image_sizes()
{
    $image_sizes = get_opt('awbooking_image_sizes', []);
    $return = [];
    foreach ($image_sizes as $image_size) {
        $image_size = explode('-', $image_size);
        if (is_array($image_size) && count($image_size) == 2) {
            $return[] = [$image_size[0], $image_size[1]];
        }
    }

    return array_merge([
        [1200, 900],
        [800, 600],
        [400, 300]
    ], $return);
}

function get_attachment_alt($attachment_id = '')
{
    if (empty($attachment_id)) {
        return __('Image');
    }
    $attachment = get_attachment($attachment_id);
    if ($attachment) {
        return esc_attr($attachment->media_description);
    }
    return __('Image');
}

function get_attachment($attachment_id)
{
    $media = new Media();
    return $media->getById($attachment_id);
}

function get_media_upload_permission()
{
    if (is_admin()) {
        return config('awebooking.media_uploads')['admin']['type'];
    }
    if (is_partner()) {
        return config('awebooking.media_uploads')['partner']['type'];
    }
    return config('awebooking.media_uploads')['customer']['type'];
}

function get_media_upload_size()
{
    if (is_admin()) {
        return config('awebooking.media_uploads')['admin']['size'];
    }
    if (is_partner()) {
        return config('awebooking.media_uploads')['partner']['size'];
    }
    return config('awebooking.media_uploads')['customer']['size'];
}

function get_media_upload_message()
{
    if (is_admin()) {
        return config('awebooking.media_uploads')['admin']['message'];
    }
    if (is_partner()) {
        return config('awebooking.media_uploads')['partner']['message'];
    }
    return config('awebooking.media_uploads')['customer']['message'];
}

function get_option($key = '', $default = '')
{
    return \ThemeOptions::getOption($key, $default);
}

function get_opt($option_name = '', $default = '')
{
    $option = new Option();
    $value = $option->getOption($option_name);
    if (!$value) {
        return $default;
    } else {
        return maybe_unserialize($value->option_value);
    }
}

function update_opt($option_name = '', $option_value = '')
{
    $option = new Option();
    $has_option = $option->hasOption($option_name);
    if ($has_option) {

        return $option->updateOption($option_name, maybe_serialize($option_value));
    } else {

        return $option->createOption($option_name, maybe_serialize($option_value));
    }
}

function enqueue_style($name)
{
    $enqueue = \EnqueueScripts::get_inst();
    $enqueue->_enqueueStyle($name);
}

function enqueue_script($name)
{
    $enqueue = \EnqueueScripts::get_inst();
    $enqueue->_enqueueScript($name);
}

function is_timestamp($timestamp)
{
    return ((int)$timestamp === $timestamp)
        && ($timestamp <= PHP_INT_MAX)
        && ($timestamp >= ~PHP_INT_MAX);
}

function hh_date_diff($start, $end, $type = 'date', $rounding = true)
{
    switch ($type) {
        case 'date':
            $start = date_create(date('Y-m-d', $start));
            $end = date_create(date('Y-m-d', $end));
            $diff = date_diff($start, $end);

            return $diff->format("%a");
            break;
        case 'hour':
            $diff = $end - $start;
            $minute = (int)($diff / 60);
            if ($minute <= 0) {
                $minute = 1;
            }
            if ($diff % 60) {
                $minute += 1;
            }

            return ceil($minute / 60);
            break;
        case 'minute':
            $diff = $end - $start;
            $minute = (int)($diff / 60);
            if ($minute <= 0) {
                $minute = 1;
            }
            if ($diff % 60) {
                $minute += 1;
            }

            return $minute;
            break;
        case 'second':
            return $end - $start;
            break;
    }

}

function is_weekend($timestamp, $rule = 'sun')
{
    $rules = [
        'sun' => [0],
        'sat_sun' => [0, 6],
        'fri_sat' => [5, 6],
        'fri_sat_sun' => [0, 5, 6]
    ];

    $weekDay = date('w', $timestamp);
    if (isset($rules[$rule])) {
        return (in_array($weekDay, $rules[$rule])) ? true : false;
    } else {
        return false;
    }

}

function hh_encrypt($string)
{
    $key_encrypt = Config::get('awebooking.key_encrypt');

    return md5(md5($key_encrypt) . md5($string));
}

function hh_compare_encrypt($string = '', $encrypt = '')
{
    $key_encrypt = Config::get('awebooking.key_encrypt');
    $string = md5(md5($key_encrypt) . md5($string));
    if (!empty($string) && !empty($encrypt) && $string === $encrypt) {
        return true;
    }

    return false;
}

function maybe_unserialize($original)
{
    if (is_serialized($original)) {
        return @unserialize($original);
    }
    return $original;
}

function maybe_serialize($data)
{
    if (is_array($data) || is_object($data)) {
        return serialize($data);
    }

    if (is_serialized($data, false)) {
        return serialize($data);
    }

    return $data;
}

function is_serialized($data, $strict = true)
{
    if (!is_string($data)) {
        return false;
    }
    $data = trim($data);
    if ('N;' == $data) {
        return true;
    }
    if (strlen($data) < 4) {
        return false;
    }
    if (':' !== $data[1]) {
        return false;
    }
    if ($strict) {
        $lastc = substr($data, -1);
        if (';' !== $lastc && '}' !== $lastc) {
            return false;
        }
    } else {
        $semicolon = strpos($data, ';');
        $brace = strpos($data, '}');
        // Either ; or } must exist.
        if (false === $semicolon && false === $brace) {
            return false;
        }
        // But neither must be in the first X characters.
        if (false !== $semicolon && $semicolon < 3) {
            return false;
        }
        if (false !== $brace && $brace < 4) {
            return false;
        }
    }
    $token = $data[0];
    switch ($token) {
        case 's':
            if ($strict) {
                if ('"' !== substr($data, -2, 1)) {
                    return false;
                }
            } elseif (false === strpos($data, '"')) {
                return false;
            }
        case 'a':
        case 'O':
            return (bool)preg_match("/^{$token}:[0-9]+:/s", $data);
        case 'b':
        case 'i':
        case 'd':
            $end = $strict ? '$' : '';
            return (bool)preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
    }
    return false;
}

function get_icon($name = '', $color = '', $width = '', $height = '', $stroke = false, $force = false)
{
    $is_dashboard = is_dashboard();

    global $hh_fonts, $hh_lazyload;
    $class = '';
    if ($hh_lazyload == 'off' || is_null($hh_lazyload) || $force || $is_dashboard) {
        if (!$hh_fonts) {
            include_once public_path('fonts/fonts.php');
            include_once public_path('fonts/fonts-system.php');
            $fonts_merge = [];
            if (isset($fonts)) {
                $fonts_merge = $fonts;
            }
            if (isset($fonts_system)) {
                $fonts_merge = array_merge($fonts_merge, $fonts_system);
            }
            $hh_fonts = $fonts_merge;
        }
        if (!isset($hh_fonts[$name])) {
            return '';
        }
        $icon = $hh_fonts[$name];
        if (!empty($color)) {
            if ($stroke) {
                $icon = preg_replace('/stroke="(.{7})"/', 'stroke="' . $color . '"', $icon);
                $icon = preg_replace('/stroke:(.{7})/', 'stroke:' . $color, $icon);
            } else {
                $icon = preg_replace('/fill="(.{7})"/', 'fill="' . $color . '"', $icon);
                $icon = preg_replace('/fill:(.{7})/', 'fill:' . $color, $icon);
            }
        }

        if (!empty($width)) {
            $icon = preg_replace('/width="(\d{2}[a-z]{2})"/', 'width="' . $width . '"', $icon);
        }

        if (!empty($height)) {
            $icon = preg_replace('/height="(\d{2}[a-z]{2})"/', 'height="' . $height . '"', $icon);
        }
    } else {
        $class = 'parent-lazy-svg';
        $stroke = $stroke ? 'true' : 'false';
        $icon = '<span class="lazy-svg" data-name="' . esc_attr($name) .
            '" data-color="' . esc_attr($color) .
            '" data-width="' . esc_attr($width) .
            '" data-height="' . esc_attr($height)
            . '" data-stroke="' . esc_attr($stroke) . '"></span>';
    }

    return '<i class="hh-icon ' . $class . ' fa">' . $icon . '</i>';
}

function hh_date_format($time = false)
{
    if ($time) {
        return get_option('time_format', Config::get('awebooking.date_format')) . ' ' . get_option('date_format', Config::get('awebooking.time_format'));
    } else {
        return get_option('date_format', Config::get('awebooking.date_format'));
    }
}

function hh_get_date_from_request($date = '', $from_format = 'Y-m-d')
{
    if (empty($date)) {
        $date = request('checkIn', '');
    }

    if (empty($date)) {
        return __('Unknown');
    }
    $date = date_create_from_format($from_format, $date);
    if ($date) {
        return date_format($date, hh_date_format());
    }
    return __('Unknown');
}

function hh_date_format_moment()
{
    $format = hh_date_format();
    $format = str_replace('j', 'd', $format);
    $format = str_replace('S', 'd', $format);
    $format = str_replace('n', 'm', $format);

    $ori_format = [
        'd' => 'DD',
        'F' => 'MMMM',
        'M' => 'MMM',
        'm' => 'MM',
        'Y' => 'YYYY',
        'y' => 'YY',
    ];

    preg_match_all("/[a-zA-Z]/", $format, $out);

    $out = $out[0];
    foreach ($out as $key => $val) {
        foreach ($ori_format as $ori_key => $ori_val) {
            if ($val == $ori_key) {
                $format = str_replace($val, $ori_val, $format);
            }
        }
    }

    return $format;
}

function hh_time_type_picker()
{
    $format = hh_time_format();
    if (substr($format, 0, 1) == 'H') {
        return 24;
    } else {
        return 12;
    }
}

function hh_time_format_picker()
{
    $format = hh_time_format();
    $format = str_replace('A', 'K', $format);
    return $format;
}

function hh_time_format()
{
    return get_option('time_format', Config::get('awebooking.time_format'));
}

function remove_query_arg($key, $query = false)
{
    if (is_array($key)) { // removing multiple keys
        foreach ($key as $k) {
            $query = add_query_arg($k, false, $query);
        }
        return $query;
    }
    return add_query_arg($key, false, $query);
}

function add_query_arg(...$args)
{
    $args = func_get_args();
    if (is_array($args[0])) {
        if (count($args) < 2 || false === $args[1]) {
            $uri = $_SERVER['REQUEST_URI'];
        } else {
            $uri = $args[1];
        }
    } else {
        if (count($args) < 3 || false === $args[2]) {
            $uri = $_SERVER['REQUEST_URI'];
        } else {
            $uri = $args[2];
        }
    }

    if ($frag = strstr($uri, '#')) {
        $uri = substr($uri, 0, -strlen($frag));
    } else {
        $frag = '';
    }

    if (0 === stripos($uri, 'http://')) {
        $protocol = 'http://';
        $uri = substr($uri, 7);
    } elseif (0 === stripos($uri, 'https://')) {
        $protocol = 'https://';
        $uri = substr($uri, 8);
    } else {
        $protocol = '';
    }

    if (strpos($uri, '?') !== false) {
        list($base, $query) = explode('?', $uri, 2);
        $base .= '?';
    } elseif ($protocol || strpos($uri, '=') === false) {
        $base = $uri . '?';
        $query = '';
    } else {
        $base = '';
        $query = $uri;
    }

    wp_parse_str($query, $qs);
    $qs = urlencode_deep($qs); // this re-URL-encodes things that were already in the query string
    if (is_array($args[0])) {
        foreach ($args[0] as $k => $v) {
            $qs[$k] = $v;
        }
    } else {
        $qs[$args[0]] = $args[1];
    }

    foreach ($qs as $k => $v) {
        if ($v === false) {
            unset($qs[$k]);
        }
    }

    $ret = build_query($qs);
    $ret = trim($ret, '?');
    $ret = preg_replace('#=(&|$)#', '$1', $ret);
    $ret = $protocol . $base . $ret . $frag;
    $ret = rtrim($ret, '?');
    return $ret;
}

function urlencode_deep($value)
{
    return map_deep($value, 'urlencode');
}

function build_query($data)
{
    return _http_build_query($data, null, '&', '', false);
}

function wp_parse_str($string, &$array)
{
    parse_str($string, $array);
    /**
     * Filters the array of variables derived from a parsed string.
     *
     * @param array $array The array populated with variables.
     * @since 2.3.0
     *
     */
    $array = apply_filters('wp_parse_str', $array);
}

function stripslashes_deep($value)
{
    return map_deep($value, 'stripslashes_from_strings_only');
}

function stripslashes_from_strings_only($value)
{
    return is_string($value) ? stripslashes($value) : $value;
}

function map_deep($value, $callback)
{
    if (is_array($value)) {
        foreach ($value as $index => $item) {
            $value[$index] = map_deep($item, $callback);
        }
    } elseif (is_object($value)) {
        $object_vars = get_object_vars($value);
        foreach ($object_vars as $property_name => $property_value) {
            $value->$property_name = map_deep($property_value, $callback);
        }
    } else {
        $value = call_user_func($callback, $value);
    }

    return $value;
}

function _http_build_query($data, $prefix = null, $sep = null, $key = '', $urlencode = true)
{
    $ret = array();

    foreach ((array)$data as $k => $v) {
        if ($urlencode) {
            $k = urlencode($k);
        }
        if (is_int($k) && $prefix != null) {
            $k = $prefix . $k;
        }
        if (!empty($key)) {
            $k = $key . '%5B' . $k . '%5D';
        }
        if ($v === null) {
            continue;
        } elseif ($v === false) {
            $v = '0';
        }

        if (is_array($v) || is_object($v)) {
            array_push($ret, _http_build_query($v, '', $sep, $k, $urlencode));
        } elseif ($urlencode) {
            array_push($ret, $k . '=' . urlencode($v));
        } else {
            array_push($ret, $k . '=' . $v);
        }
    }

    if (null === $sep) {
        $sep = ini_get('arg_separator.output');
    }

    return implode($sep, $ret);
}

function wp_parse_args($args, $defaults = '')
{
    if (is_object($args)) {
        $r = get_object_vars($args);
    } elseif (is_array($args)) {
        $r =& $args;
    } else {
        wp_parse_str($args, $r);
    }

    if (is_array($defaults)) {
        foreach ($defaults as $key => $value) {
            if (isset($r[$key]) && !empty($r[$key])) {
                $defaults[$key] = $r[$key];
            }
        }
        return $defaults;
    }
    return $r;
}

function frontend_pagination($args = [], $comment = false)
{
    $defaults = [
        'range' => 4,
        'total' => 0,
        'previous_string' => '<i class="icon-arrow-left"></i>',
        'next_string' => '<i class="icon-arrow-right"></i>',
        'before_output' => '<nav aria-label="navigation"><ul class="pagination">',
        'after_output' => '</ul></nav>',
        'posts_per_page' => posts_per_page(),
        'query_string' => '',
        'current_url' => '',
        'page' => 1,
        'query_page' => true,
        'slug' => '',
        'type' => '',
        'force_query_false' => 1
    ];

    if ($comment) {
        $defaults['posts_per_page'] = comments_per_page();
    } else {
        $page_slug = 'page';
        $defaults['posts_per_page'] = posts_per_page();
    }

    $args = wp_parse_args($args, $defaults);

    if ($comment) {
        if ($args['type'] == 'home') {
            $page_slug = 'review_page';
        } else {
            $page_slug = 'comment_page';
        }
    }

    $query_page = $args['query_page'];
    if (isset($args['force_query_false']) && $args['force_query_false'] == -1) {
        $query_page = false;
    }

    $args['range'] = (int)$args['range'] - 1;
    $posts_per_page = $args['posts_per_page'];

    $count = ceil($args['total'] / $posts_per_page);

    $current_params = \Illuminate\Support\Facades\Route::current()->parameters();
    $totalParams = count($current_params);
    if (isset($args['slug']) && !empty($args['slug']) && isset($current_params[$args['slug']])) {
        $totalParams++;
    }

    if (isset($current_params['page'])) {
        $page = $current_params['page'];
    } else {
        $page = $args['page'];
    }

    $ceil = ceil($args['range'] / 2);
    if ($count <= 1)
        return false;
    if ($count > $args['range']) {
        if ($page <= $args['range']) {
            $min = 1;
            $max = $args['range'] + 1;
        } elseif ($page >= ($count - $ceil)) {
            $min = $count - $args['range'];
            $max = $count;
        } elseif ($page >= $args['range'] && $page < ($count - $ceil)) {
            $min = $page - $ceil;
            $max = $page + $ceil;
        }
    } else {
        $min = 1;
        $max = $count;
    }
    $echo = '';

    $paramTemp = '';
    if (empty($args['query_string'])) {
        $current_params = Route::current()->parameters();
        if (!empty($current_params)) {
            $paramTemp = '/' . implode('/', $current_params);
        }
        if (!$comment) {
            $query_str = \Request::getRequestUri();
            if (strpos($query_str, '?')) {
                $query_str = substr($query_str, strpos($query_str, '?'), strlen($query_str));
            } else {
                $query_str = '';
            }
        } else {
            $query_str = $paramTemp;
        }
    } else {
        $query_str = $args['query_string'];
    }

    if (!empty($args['current_url'])) {
        $url = $args['current_url'];
    } else {
        $url = url(Route::currentRouteName());
    }

    $previous = $url;

    if ($previous && (1 == $page)) {
        $echo .= '<li class="disabled"><a class="page-link previous" href="javascript:void(0);" title="previous">' . $args['previous_string'] . '</a></li>';
    }
    if ($previous && (1 != $page)) {
        if (!$query_page) {
            $previous_num = intval($page) - 1;
            $query_str_temp = '';
            switch ($totalParams) {
                case 0:
                case 1:
                    $previous = $url . '/' . $previous_num . '/';
                    break;
                case 2:
                case 3:
                    $previous = $url . '/' . $current_params[$args['slug']] . '/' . $previous_num . '/';
                    break;
            }
        } else {
            $query_str_temp = $query_str;
            $previous_num = intval($page) - 1;
            if (strpos($query_str, '?')) {
                $query_str_temp .= '&' . $page_slug . '=' . $previous_num;
            } else {
                $query_str_temp .= '?' . $page_slug . '=' . $previous_num;
            }
        }
        $echo .= '<li class="page-item"><a class="page-link previous" data-pagination="' . $previous_num . '" href="' . $previous . $query_str_temp . '" title="previous">' . $args['previous_string'] . '</a></li>';
    }
    if (!empty($min) && !empty($max)) {
        for ($i = $min; $i <= $max; $i++) {
            if ($page == $i) {
                $echo .= '<li class="active page-item"><a class="page-link" data-pagination="' . $i . '" href="javascript:void(0);">' . str_pad((int)$i, 1, '0', STR_PAD_LEFT) . '</a></li>';
            } else {
                $_url = $url;
                if (!$query_page) {
                    $query_str_temp = '';
                    switch ($totalParams) {
                        case 0:
                        case 1:
                            $_url = $url . '/' . $i . '/';
                            break;
                        case 2:
                        case 3:
                            $_url = $url . '/' . $current_params[$args['slug']] . '/' . $i . '/';
                            break;
                    }
                } else {
                    $query_str_temp = $query_str;
                    if (strpos($query_str, '?')) {
                        $query_str_temp .= '&' . $page_slug . '=' . $i;
                    } else {
                        $query_str_temp .= '?' . $page_slug . '=' . $i;
                    }
                }
                $echo .= sprintf('<li class="page-item"><a class="page-link" data-pagination="' . $i . '" href="%s">%2d</a></li>', $_url . $query_str_temp, $i);
            }
        }
    }

    $next = $url;

    if ($next && ($count == $page)) {
        $echo .= '<li class="disabled"><a class="page-link next" href="javascript:void(0);" title="next">' . $args['next_string'] . '</a></li>';
    }
    if ($next && ($count != $page)) {
        if (!$query_page) {
            $query_str_temp = '';
            $next_num = intval($page) + 1;
            switch ($totalParams) {
                case 0:
                case 1:
                    $next = $url . '/' . $next_num . '/';
                    break;
                case 2:
                case 3:
                    $next = $url . '/' . $current_params[$args['slug']] . '/' . $next_num . '/';
                    break;
            }
        } else {
            $query_str_temp = $query_str;
            $next_num = intval($page) + 1;
            if (strpos($query_str, '?')) {
                $query_str_temp .= '&' . $page_slug . '=' . $next_num;
            } else {
                $query_str_temp .= '?' . $page_slug . '=' . $next_num;
            }
        }
        $echo .= '<li class="page-item"><a class="page-link next" data-pagination="' . $next_num . '" href="' . $next . $query_str_temp . '" title="next">' . $args['next_string'] . '</a></li>';
    }
    if (isset($echo))
        echo balanceTags($args['before_output'] . $echo . $args['after_output']);
}

function get_time_since($older_date, $newer_date = false)
{
    $unknown_text = 'sometime';
    $right_now_text = 'right now';
    $ago_text = '%s ago';
    $chunks = [
        [60 * 60 * 24 * 365, 'year', 'years'],
        [60 * 60 * 24 * 30, 'month', 'months'],
        [60 * 60 * 24 * 7, 'week', 'weeks'],
        [60 * 60 * 24, 'day', 'days'],
        [60 * 60, 'hour', 'hours'],
        [60, 'minute', 'minutes'],
        [1, 'second', 'seconds']
    ];
    $newer_date = (!$newer_date) ? time() : $newer_date;
    $since = $newer_date - $older_date;

    $date_old = strtotime(date('Y-m-d', $older_date));
    $date_new = strtotime(date('Y-m-d'));
    if ($date_old < $date_new) {
        return date(hh_date_format(), $older_date);
    } elseif ($since >= 3600) {
        return date(hh_time_format(), $older_date);
    }

    if (0 > $since) {
        $output = $unknown_text;
    } else {
        for ($i = 0, $j = count($chunks); $i < $j; ++$i) {
            $seconds = $chunks[$i][0];
            $count = floor($since / $seconds);
            if (0 != $count) {
                break;
            }
        }

        if (!isset($chunks[$i])) {
            $output = $right_now_text;
        } else {
            $output = (1 == $count) ? '1 ' . $chunks[$i][1] : $count . ' ' . $chunks[$i][2];
            if ($i + 2 < $j) {
                $seconds2 = $chunks[$i + 1][0];
                $name2 = $chunks[$i + 1][1];
                $count2 = floor(($since - ($seconds * $count)) / $seconds2);

                if (0 != $count2) {
                    $output .= (1 == $count2) ? ',' . ' 1 ' . $name2 : ',' . ' ' . $count2 . ' ' . $chunks[$i + 1][2];
                }
            }
            if (!(int)trim($output)) {
                $output = $right_now_text;
            }
        }
    }

    if ($output != $right_now_text) {
        $output = sprintf($ago_text, $output);
    }

    return $output;
}

function get_country_by_code($code, $return = '')
{
    $countries = list_countries();
    $data = (isset($countries[$code])) ? $countries[$code] : ['name' => '', 'flag' => ''];
    if ($return) {
        return isset($data[$return]) ? $data[$return] : '';
    }
    return $data;
}

function list_countries($key = null)
{
    $countries = [];
    $countries_data = file_get_contents(public_path('vendor/countries/countries.json'));
    $countries_data = json_decode($countries_data, true);
    if (!empty($countries_data) && is_array($countries_data)) {
        foreach ($countries_data as $country) {
            $countries[$country['id']] = [
                'name' => $country['name'],
                'flag128' => '<img class="mr-1" src="' . asset('vendor/countries/flag/128x128/' . $country['alpha2'] . '.png') . '">',
                'flag64' => '<img class="mr-1" src="' . asset('vendor/countries/flag/64x64/' . $country['alpha2'] . '.png') . '">',
                'flag48' => '<img class="mr-1" src="' . asset('vendor/countries/flag/48x48/' . $country['alpha2'] . '.png') . '">',
                'flag32' => '<img class="mr-1" src="' . asset('vendor/countries/flag/32x32/' . $country['alpha2'] . '.png') . '">',
                'flag24' => '<img class="mr-1" src="' . asset('vendor/countries/flag/24x24/' . $country['alpha2'] . '.png') . '">',
                'flag16' => '<img class="mr-1" src="' . asset('vendor/countries/flag/16x16/' . $country['alpha2'] . '.png') . '">',
            ];
        }
    }

    if ($key) {
        return (isset($countries[$key])) ? $countries[$key] : ['name' => '', 'flag' => ''];
    }
    return $countries;
}

function get_time_zone()
{
    $timezones = array(
        'America' => array(
            'America/Adak' => 'Adak -10:00',
            'America/Atka' => 'Atka -10:00',
            'America/Anchorage' => 'Anchorage -9:00',
            'America/Juneau' => 'Juneau -9:00',
            'America/Nome' => 'Nome -9:00',
            'America/Yakutat' => 'Yakutat -9:00',
            'America/Dawson' => 'Dawson -8:00',
            'America/Ensenada' => 'Ensenada -8:00',
            'America/Los_Angeles' => 'Los_Angeles -8:00',
            'America/Tijuana' => 'Tijuana -8:00',
            'America/Vancouver' => 'Vancouver -8:00',
            'America/Whitehorse' => 'Whitehorse -8:00',
            'America/Boise' => 'Boise -7:00',
            'America/Cambridge_Bay' => 'Cambridge_Bay -7:00',
            'America/Chihuahua' => 'Chihuahua -7:00',
            'America/Dawson_Creek' => 'Dawson_Creek -7:00',
            'America/Denver' => 'Denver -7:00',
            'America/Edmonton' => 'Edmonton -7:00',
            'America/Hermosillo' => 'Hermosillo -7:00',
            'America/Inuvik' => 'Inuvik -7:00',
            'America/Mazatlan' => 'Mazatlan -7:00',
            'America/Phoenix' => 'Phoenix -7:00',
            'America/Shiprock' => 'Shiprock -7:00',
            'America/Yellowknife' => 'Yellowknife -7:00',
            'America/Belize' => 'Belize -6:00',
            'America/Cancun' => 'Cancun -6:00',
            'America/Chicago' => 'Chicago -6:00',
            'America/Costa_Rica' => 'Costa_Rica -6:00',
            'America/El_Salvador' => 'El_Salvador -6:00',
            'America/Guatemala' => 'Guatemala -6:00',
            'America/Knox_IN' => 'Knox_IN -6:00',
            'America/Managua' => 'Managua -6:00',
            'America/Menominee' => 'Menominee -6:00',
            'America/Merida' => 'Merida -6:00',
            'America/Mexico_City' => 'Mexico_City -6:00',
            'America/Monterrey' => 'Monterrey -6:00',
            'America/Rainy_River' => 'Rainy_River -6:00',
            'America/Rankin_Inlet' => 'Rankin_Inlet -6:00',
            'America/Regina' => 'Regina -6:00',
            'America/Swift_Current' => 'Swift_Current -6:00',
            'America/Tegucigalpa' => 'Tegucigalpa -6:00',
            'America/Winnipeg' => 'Winnipeg -6:00',
            'America/Atikokan' => 'Atikokan -5:00',
            'America/Bogota' => 'Bogota -5:00',
            'America/Cayman' => 'Cayman -5:00',
            'America/Coral_Harbour' => 'Coral_Harbour -5:00',
            'America/Detroit' => 'Detroit -5:00',
            'America/Fort_Wayne' => 'Fort_Wayne -5:00',
            'America/Grand_Turk' => 'Grand_Turk -5:00',
            'America/Guayaquil' => 'Guayaquil -5:00',
            'America/Havana' => 'Havana -5:00',
            'America/Indianapolis' => 'Indianapolis -5:00',
            'America/Iqaluit' => 'Iqaluit -5:00',
            'America/Jamaica' => 'Jamaica -5:00',
            'America/Lima' => 'Lima -5:00',
            'America/Louisville' => 'Louisville -5:00',
            'America/Montreal' => 'Montreal -5:00',
            'America/Nassau' => 'Nassau -5:00',
            'America/New_York' => 'New_York -5:00',
            'America/Nipigon' => 'Nipigon -5:00',
            'America/Panama' => 'Panama -5:00',
            'America/Pangnirtung' => 'Pangnirtung -5:00',
            'America/Port-au-Prince' => 'Port-au-Prince -5:00',
            'America/Resolute' => 'Resolute -5:00',
            'America/Thunder_Bay' => 'Thunder_Bay -5:00',
            'America/Toronto' => 'Toronto -5:00',
            'America/Caracas' => 'Caracas -4:-30',
            'America/Anguilla' => 'Anguilla -4:00',
            'America/Antigua' => 'Antigua -4:00',
            'America/Aruba' => 'Aruba -4:00',
            'America/Asuncion' => 'Asuncion -4:00',
            'America/Barbados' => 'Barbados -4:00',
            'America/Blanc-Sablon' => 'Blanc-Sablon -4:00',
            'America/Boa_Vista' => 'Boa_Vista -4:00',
            'America/Campo_Grande' => 'Campo_Grande -4:00',
            'America/Cuiaba' => 'Cuiaba -4:00',
            'America/Curacao' => 'Curacao -4:00',
            'America/Dominica' => 'Dominica -4:00',
            'America/Eirunepe' => 'Eirunepe -4:00',
            'America/Glace_Bay' => 'Glace_Bay -4:00',
            'America/Goose_Bay' => 'Goose_Bay -4:00',
            'America/Grenada' => 'Grenada -4:00',
            'America/Guadeloupe' => 'Guadeloupe -4:00',
            'America/Guyana' => 'Guyana -4:00',
            'America/Halifax' => 'Halifax -4:00',
            'America/La_Paz' => 'La_Paz -4:00',
            'America/Manaus' => 'Manaus -4:00',
            'America/Marigot' => 'Marigot -4:00',
            'America/Martinique' => 'Martinique -4:00',
            'America/Moncton' => 'Moncton -4:00',
            'America/Montserrat' => 'Montserrat -4:00',
            'America/Port_of_Spain' => 'Port_of_Spain -4:00',
            'America/Porto_Acre' => 'Porto_Acre -4:00',
            'America/Porto_Velho' => 'Porto_Velho -4:00',
            'America/Puerto_Rico' => 'Puerto_Rico -4:00',
            'America/Rio_Branco' => 'Rio_Branco -4:00',
            'America/Santiago' => 'Santiago -4:00',
            'America/Santo_Domingo' => 'Santo_Domingo -4:00',
            'America/St_Barthelemy' => 'St_Barthelemy -4:00',
            'America/St_Kitts' => 'St_Kitts -4:00',
            'America/St_Lucia' => 'St_Lucia -4:00',
            'America/St_Thomas' => 'St_Thomas -4:00',
            'America/St_Vincent' => 'St_Vincent -4:00',
            'America/Thule' => 'Thule -4:00',
            'America/Tortola' => 'Tortola -4:00',
            'America/Virgin' => 'Virgin -4:00',
            'America/St_Johns' => 'St_Johns -3:-30',
            'America/Araguaina' => 'Araguaina -3:00',
            'America/Bahia' => 'Bahia -3:00',
            'America/Belem' => 'Belem -3:00',
            'America/Buenos_Aires' => 'Buenos_Aires -3:00',
            'America/Catamarca' => 'Catamarca -3:00',
            'America/Cayenne' => 'Cayenne -3:00',
            'America/Cordoba' => 'Cordoba -3:00',
            'America/Fortaleza' => 'Fortaleza -3:00',
            'America/Godthab' => 'Godthab -3:00',
            'America/Jujuy' => 'Jujuy -3:00',
            'America/Maceio' => 'Maceio -3:00',
            'America/Mendoza' => 'Mendoza -3:00',
            'America/Miquelon' => 'Miquelon -3:00',
            'America/Montevideo' => 'Montevideo -3:00',
            'America/Paramaribo' => 'Paramaribo -3:00',
            'America/Recife' => 'Recife -3:00',
            'America/Rosario' => 'Rosario -3:00',
            'America/Santarem' => 'Santarem -3:00',
            'America/Sao_Paulo' => 'Sao_Paulo -3:00',
            'America/Noronha' => 'Noronha -2:00',
            'America/Scoresbysund' => 'Scoresbysund -1:00',
            'America/Danmarkshavn' => 'Danmarkshavn +0:00',
        ),
        'Canada' => array(
            'Canada/Pacific' => 'Pacific -8:00',
            'Canada/Yukon' => 'Yukon -8:00',
            'Canada/Mountain' => 'Mountain -7:00',
            'Canada/Central' => 'Central -6:00',
            'Canada/East-Saskatchewan' => 'East-Saskatchewan -6:00',
            'Canada/Saskatchewan' => 'Saskatchewan -6:00',
            'Canada/Eastern' => 'Eastern -5:00',
            'Canada/Atlantic' => 'Atlantic -4:00',
            'Canada/Newfoundland' => 'Newfoundland -3:-30',
        ),
        'Mexico' => array(
            'Mexico/BajaNorte' => 'BajaNorte -8:00',
            'Mexico/BajaSur' => 'BajaSur -7:00',
            'Mexico/General' => 'General -6:00',
        ),
        'Chile' => array(
            'Chile/EasterIsland' => 'EasterIsland -6:00',
            'Chile/Continental' => 'Continental -4:00',
        ),
        'Antarctica' => array(
            'Antarctica/Palmer' => 'Palmer -4:00',
            'Antarctica/Rothera' => 'Rothera -3:00',
            'Antarctica/Syowa' => 'Syowa +3:00',
            'Antarctica/Mawson' => 'Mawson +6:00',
            'Antarctica/Vostok' => 'Vostok +6:00',
            'Antarctica/Davis' => 'Davis +7:00',
            'Antarctica/Casey' => 'Casey +8:00',
            'Antarctica/DumontDUrville' => 'DumontDUrville +10:00',
            'Antarctica/McMurdo' => 'McMurdo +12:00',
            'Antarctica/South_Pole' => 'South_Pole +12:00',
        ),
        'Atlantic' => array(
            'Atlantic/Bermuda' => 'Bermuda -4:00',
            'Atlantic/Stanley' => 'Stanley -4:00',
            'Atlantic/South_Georgia' => 'South_Georgia -2:00',
            'Atlantic/Azores' => 'Azores -1:00',
            'Atlantic/Cape_Verde' => 'Cape_Verde -1:00',
            'Atlantic/Canary' => 'Canary +0:00',
            'Atlantic/Faeroe' => 'Faeroe +0:00',
            'Atlantic/Faroe' => 'Faroe +0:00',
            'Atlantic/Madeira' => 'Madeira +0:00',
            'Atlantic/Reykjavik' => 'Reykjavik +0:00',
            'Atlantic/St_Helena' => 'St_Helena +0:00',
            'Atlantic/Jan_Mayen' => 'Jan_Mayen +1:00',
        ),
        'Brazil' => array(
            'Brazil/Acre' => 'Acre -4:00',
            'Brazil/West' => 'West -4:00',
            'Brazil/East' => 'East -3:00',
            'Brazil/DeNoronha' => 'DeNoronha -2:00',
        ),
        'Africa' => array(
            'Africa/Abidjan' => 'Abidjan +0:00',
            'Africa/Accra' => 'Accra +0:00',
            'Africa/Bamako' => 'Bamako +0:00',
            'Africa/Banjul' => 'Banjul +0:00',
            'Africa/Bissau' => 'Bissau +0:00',
            'Africa/Casablanca' => 'Casablanca +0:00',
            'Africa/Conakry' => 'Conakry +0:00',
            'Africa/Dakar' => 'Dakar +0:00',
            'Africa/El_Aaiun' => 'El_Aaiun +0:00',
            'Africa/Freetown' => 'Freetown +0:00',
            'Africa/Lome' => 'Lome +0:00',
            'Africa/Monrovia' => 'Monrovia +0:00',
            'Africa/Nouakchott' => 'Nouakchott +0:00',
            'Africa/Ouagadougou' => 'Ouagadougou +0:00',
            'Africa/Sao_Tome' => 'Sao_Tome +0:00',
            'Africa/Timbuktu' => 'Timbuktu +0:00',
            'Africa/Algiers' => 'Algiers +1:00',
            'Africa/Bangui' => 'Bangui +1:00',
            'Africa/Brazzaville' => 'Brazzaville +1:00',
            'Africa/Ceuta' => 'Ceuta +1:00',
            'Africa/Douala' => 'Douala +1:00',
            'Africa/Kinshasa' => 'Kinshasa +1:00',
            'Africa/Lagos' => 'Lagos +1:00',
            'Africa/Libreville' => 'Libreville +1:00',
            'Africa/Luanda' => 'Luanda +1:00',
            'Africa/Malabo' => 'Malabo +1:00',
            'Africa/Ndjamena' => 'Ndjamena +1:00',
            'Africa/Niamey' => 'Niamey +1:00',
            'Africa/Porto-Novo' => 'Porto-Novo +1:00',
            'Africa/Tunis' => 'Tunis +1:00',
            'Africa/Windhoek' => 'Windhoek +1:00',
            'Africa/Blantyre' => 'Blantyre +2:00',
            'Africa/Bujumbura' => 'Bujumbura +2:00',
            'Africa/Cairo' => 'Cairo +2:00',
            'Africa/Gaborone' => 'Gaborone +2:00',
            'Africa/Harare' => 'Harare +2:00',
            'Africa/Johannesburg' => 'Johannesburg +2:00',
            'Africa/Kigali' => 'Kigali +2:00',
            'Africa/Lubumbashi' => 'Lubumbashi +2:00',
            'Africa/Lusaka' => 'Lusaka +2:00',
            'Africa/Maputo' => 'Maputo +2:00',
            'Africa/Maseru' => 'Maseru +2:00',
            'Africa/Mbabane' => 'Mbabane +2:00',
            'Africa/Tripoli' => 'Tripoli +2:00',
            'Africa/Addis_Ababa' => 'Addis_Ababa +3:00',
            'Africa/Asmara' => 'Asmara +3:00',
            'Africa/Asmera' => 'Asmera +3:00',
            'Africa/Dar_es_Salaam' => 'Dar_es_Salaam +3:00',
            'Africa/Djibouti' => 'Djibouti +3:00',
            'Africa/Kampala' => 'Kampala +3:00',
            'Africa/Khartoum' => 'Khartoum +3:00',
            'Africa/Mogadishu' => 'Mogadishu +3:00',
            'Africa/Nairobi' => 'Nairobi +3:00',
        ),
        'Europe' => array(
            'Europe/Belfast' => 'Belfast +0:00',
            'Europe/Dublin' => 'Dublin +0:00',
            'Europe/Guernsey' => 'Guernsey +0:00',
            'Europe/Isle_of_Man' => 'Isle_of_Man +0:00',
            'Europe/Jersey' => 'Jersey +0:00',
            'Europe/Lisbon' => 'Lisbon +0:00',
            'Europe/London' => 'London +0:00',
            'Europe/Amsterdam' => 'Amsterdam +1:00',
            'Europe/Andorra' => 'Andorra +1:00',
            'Europe/Belgrade' => 'Belgrade +1:00',
            'Europe/Berlin' => 'Berlin +1:00',
            'Europe/Bratislava' => 'Bratislava +1:00',
            'Europe/Brussels' => 'Brussels +1:00',
            'Europe/Budapest' => 'Budapest +1:00',
            'Europe/Copenhagen' => 'Copenhagen +1:00',
            'Europe/Gibraltar' => 'Gibraltar +1:00',
            'Europe/Ljubljana' => 'Ljubljana +1:00',
            'Europe/Luxembourg' => 'Luxembourg +1:00',
            'Europe/Madrid' => 'Madrid +1:00',
            'Europe/Malta' => 'Malta +1:00',
            'Europe/Monaco' => 'Monaco +1:00',
            'Europe/Oslo' => 'Oslo +1:00',
            'Europe/Paris' => 'Paris +1:00',
            'Europe/Podgorica' => 'Podgorica +1:00',
            'Europe/Prague' => 'Prague +1:00',
            'Europe/Rome' => 'Rome +1:00',
            'Europe/San_Marino' => 'San_Marino +1:00',
            'Europe/Sarajevo' => 'Sarajevo +1:00',
            'Europe/Skopje' => 'Skopje +1:00',
            'Europe/Stockholm' => 'Stockholm +1:00',
            'Europe/Tirane' => 'Tirane +1:00',
            'Europe/Vaduz' => 'Vaduz +1:00',
            'Europe/Vatican' => 'Vatican +1:00',
            'Europe/Vienna' => 'Vienna +1:00',
            'Europe/Warsaw' => 'Warsaw +1:00',
            'Europe/Zagreb' => 'Zagreb +1:00',
            'Europe/Zurich' => 'Zurich +1:00',
            'Europe/Athens' => 'Athens +2:00',
            'Europe/Bucharest' => 'Bucharest +2:00',
            'Europe/Chisinau' => 'Chisinau +2:00',
            'Europe/Helsinki' => 'Helsinki +2:00',
            'Europe/Istanbul' => 'Istanbul +2:00',
            'Europe/Kaliningrad' => 'Kaliningrad +2:00',
            'Europe/Kiev' => 'Kiev +2:00',
            'Europe/Mariehamn' => 'Mariehamn +2:00',
            'Europe/Minsk' => 'Minsk +2:00',
            'Europe/Nicosia' => 'Nicosia +2:00',
            'Europe/Riga' => 'Riga +2:00',
            'Europe/Simferopol' => 'Simferopol +2:00',
            'Europe/Sofia' => 'Sofia +2:00',
            'Europe/Tallinn' => 'Tallinn +2:00',
            'Europe/Tiraspol' => 'Tiraspol +2:00',
            'Europe/Uzhgorod' => 'Uzhgorod +2:00',
            'Europe/Vilnius' => 'Vilnius +2:00',
            'Europe/Zaporozhye' => 'Zaporozhye +2:00',
            'Europe/Moscow' => 'Moscow +3:00',
            'Europe/Volgograd' => 'Volgograd +3:00',
            'Europe/Samara' => 'Samara +4:00',
        ),
        'Arctic' => array(
            'Arctic/Longyearbyen' => 'Longyearbyen +1:00',
        ),
        'Asia' => array(
            'Asia/Amman' => 'Amman +2:00',
            'Asia/Beirut' => 'Beirut +2:00',
            'Asia/Damascus' => 'Damascus +2:00',
            'Asia/Gaza' => 'Gaza +2:00',
            'Asia/Istanbul' => 'Istanbul +2:00',
            'Asia/Jerusalem' => 'Jerusalem +2:00',
            'Asia/Nicosia' => 'Nicosia +2:00',
            'Asia/Tel_Aviv' => 'Tel_Aviv +2:00',
            'Asia/Aden' => 'Aden +3:00',
            'Asia/Baghdad' => 'Baghdad +3:00',
            'Asia/Bahrain' => 'Bahrain +3:00',
            'Asia/Kuwait' => 'Kuwait +3:00',
            'Asia/Qatar' => 'Qatar +3:00',
            'Asia/Tehran' => 'Tehran +3:30',
            'Asia/Baku' => 'Baku +4:00',
            'Asia/Dubai' => 'Dubai +4:00',
            'Asia/Muscat' => 'Muscat +4:00',
            'Asia/Tbilisi' => 'Tbilisi +4:00',
            'Asia/Yerevan' => 'Yerevan +4:00',
            'Asia/Kabul' => 'Kabul +4:30',
            'Asia/Aqtau' => 'Aqtau +5:00',
            'Asia/Aqtobe' => 'Aqtobe +5:00',
            'Asia/Ashgabat' => 'Ashgabat +5:00',
            'Asia/Ashkhabad' => 'Ashkhabad +5:00',
            'Asia/Dushanbe' => 'Dushanbe +5:00',
            'Asia/Karachi' => 'Karachi +5:00',
            'Asia/Oral' => 'Oral +5:00',
            'Asia/Samarkand' => 'Samarkand +5:00',
            'Asia/Tashkent' => 'Tashkent +5:00',
            'Asia/Yekaterinburg' => 'Yekaterinburg +5:00',
            'Asia/Calcutta' => 'Calcutta +5:30',
            'Asia/Colombo' => 'Colombo +5:30',
            'Asia/Kolkata' => 'Kolkata +5:30',
            'Asia/Katmandu' => 'Katmandu +5:45',
            'Asia/Almaty' => 'Almaty +6:00',
            'Asia/Bishkek' => 'Bishkek +6:00',
            'Asia/Dacca' => 'Dacca +6:00',
            'Asia/Dhaka' => 'Dhaka +6:00',
            'Asia/Novosibirsk' => 'Novosibirsk +6:00',
            'Asia/Omsk' => 'Omsk +6:00',
            'Asia/Qyzylorda' => 'Qyzylorda +6:00',
            'Asia/Thimbu' => 'Thimbu +6:00',
            'Asia/Thimphu' => 'Thimphu +6:00',
            'Asia/Rangoon' => 'Rangoon +6:30',
            'Asia/Bangkok' => 'Bangkok +7:00',
            'Asia/Ho_Chi_Minh' => 'Ho_Chi_Minh +7:00',
            'Asia/Hovd' => 'Hovd +7:00',
            'Asia/Jakarta' => 'Jakarta +7:00',
            'Asia/Krasnoyarsk' => 'Krasnoyarsk +7:00',
            'Asia/Phnom_Penh' => 'Phnom_Penh +7:00',
            'Asia/Pontianak' => 'Pontianak +7:00',
            'Asia/Saigon' => 'Saigon +7:00',
            'Asia/Vientiane' => 'Vientiane +7:00',
            'Asia/Brunei' => 'Brunei +8:00',
            'Asia/Choibalsan' => 'Choibalsan +8:00',
            'Asia/Chongqing' => 'Chongqing +8:00',
            'Asia/Chungking' => 'Chungking +8:00',
            'Asia/Harbin' => 'Harbin +8:00',
            'Asia/Hong_Kong' => 'Hong_Kong +8:00',
            'Asia/Irkutsk' => 'Irkutsk +8:00',
            'Asia/Kashgar' => 'Kashgar +8:00',
            'Asia/Kuala_Lumpur' => 'Kuala_Lumpur +8:00',
            'Asia/Kuching' => 'Kuching +8:00',
            'Asia/Macao' => 'Macao +8:00',
            'Asia/Macau' => 'Macau +8:00',
            'Asia/Makassar' => 'Makassar +8:00',
            'Asia/Manila' => 'Manila +8:00',
            'Asia/Shanghai' => 'Shanghai +8:00',
            'Asia/Singapore' => 'Singapore +8:00',
            'Asia/Taipei' => 'Taipei +8:00',
            'Asia/Ujung_Pandang' => 'Ujung_Pandang +8:00',
            'Asia/Ulaanbaatar' => 'Ulaanbaatar +8:00',
            'Asia/Ulan_Bator' => 'Ulan_Bator +8:00',
            'Asia/Urumqi' => 'Urumqi +8:00',
            'Asia/Dili' => 'Dili +9:00',
            'Asia/Jayapura' => 'Jayapura +9:00',
            'Asia/Pyongyang' => 'Pyongyang +9:00',
            'Asia/Seoul' => 'Seoul +9:00',
            'Asia/Tokyo' => 'Tokyo +9:00',
            'Asia/Yakutsk' => 'Yakutsk +9:00',
            'Asia/Sakhalin' => 'Sakhalin +10:00',
            'Asia/Vladivostok' => 'Vladivostok +10:00',
            'Asia/Magadan' => 'Magadan +11:00',
            'Asia/Anadyr' => 'Anadyr +12:00',
            'Asia/Kamchatka' => 'Kamchatka +12:00',
        ),
        'Indian' => array(
            'Indian/Antananarivo' => 'Antananarivo +3:00',
            'Indian/Comoro' => 'Comoro +3:00',
            'Indian/Mayotte' => 'Mayotte +3:00',
            'Indian/Mahe' => 'Mahe +4:00',
            'Indian/Mauritius' => 'Mauritius +4:00',
            'Indian/Reunion' => 'Reunion +4:00',
            'Indian/Kerguelen' => 'Kerguelen +5:00',
            'Indian/Maldives' => 'Maldives +5:00',
            'Indian/Chagos' => 'Chagos +6:00',
            'Indian/Cocos' => 'Cocos +6:30',
            'Indian/Christmas' => 'Christmas +7:00',
        ),
        'Australia' => array(
            'Australia/Perth' => 'Perth +8:00',
            'Australia/West' => 'West +8:00',
            'Australia/Eucla' => 'Eucla +8:45',
            'Australia/Adelaide' => 'Adelaide +9:30',
            'Australia/Broken_Hill' => 'Broken_Hill +9:30',
            'Australia/Darwin' => 'Darwin +9:30',
            'Australia/North' => 'North +9:30',
            'Australia/South' => 'South +9:30',
            'Australia/Yancowinna' => 'Yancowinna +9:30',
            'Australia/ACT' => 'ACT +10:00',
            'Australia/Brisbane' => 'Brisbane +10:00',
            'Australia/Canberra' => 'Canberra +10:00',
            'Australia/Currie' => 'Currie +10:00',
            'Australia/Hobart' => 'Hobart +10:00',
            'Australia/Lindeman' => 'Lindeman +10:00',
            'Australia/Melbourne' => 'Melbourne +10:00',
            'Australia/NSW' => 'NSW +10:00',
            'Australia/Queensland' => 'Queensland +10:00',
            'Australia/Sydney' => 'Sydney +10:00',
            'Australia/Tasmania' => 'Tasmania +10:00',
            'Australia/Victoria' => 'Victoria +10:00',
            'Australia/LHI' => 'LHI +10:30',
            'Australia/Lord_Howe' => 'Lord_Howe +10:30',
        ),
    );

    return $timezones;
}

function is_email($email)
{

    if (strlen($email) < 6) {
        return false;
    }

    if (strpos($email, '@', 1) === false) {
        return false;
    }

    list($local, $domain) = explode('@', $email, 2);

    if (!preg_match('/^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/', $local)) {
        return false;
    }

    if (preg_match('/\.{2,}/', $domain)) {
        return false;
    }

    if (trim($domain, " \t\n\r\0\x0B.") !== $domain) {
        return false;
    }

    $subs = explode('.', $domain);

    if (2 > count($subs)) {
        return false;
    }

    foreach ($subs as $sub) {
        if (trim($sub, " \t\n\r\0\x0B-") !== $sub) {
            return false;
        }

        if (!preg_match('/^[a-z0-9-]+$/i', $sub)) {
            return false;
        }
    }

    return $email;
}

function esc_text($text)
{
    return $text;
}

function esc_html($text)
{
    $text = trim($text);
    $safe_text = _check_invalid_utf8($text);
    $safe_text = _specialchars($safe_text, ENT_QUOTES);
    return $safe_text;
}

function esc_attr($text)
{
    $safe_text = _check_invalid_utf8($text);
    $safe_text = _specialchars($safe_text, ENT_QUOTES);
    return $safe_text;
}

function esc_sql($text)
{
    return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $text);
}

function esc_url($url)
{
    return str_replace(' ', '%20', $url);
}

function _check_invalid_utf8($string, $strip = false)
{
    $string = (string)$string;

    if (0 === strlen($string)) {
        return '';
    }

    // Store the site charset as a static to avoid multiple calls to get_option()
    static $is_utf8 = null;
    if (!isset($is_utf8)) {
        $is_utf8 = in_array('utf-8', array('utf8', 'utf-8', 'UTF8', 'UTF-8'));
    }
    if (!$is_utf8) {
        return $string;
    }

    // Check for support for utf8 in the installed PCRE library once and store the result in a static
    static $utf8_pcre = null;
    if (!isset($utf8_pcre)) {
        $utf8_pcre = @preg_match('/^./u', 'a');
    }
    // We can't demand utf8 in the PCRE installation, so just return the string in those cases
    if (!$utf8_pcre) {
        return $string;
    }

    // preg_match fails when it encounters invalid UTF8 in $string
    if (1 === @preg_match('/^./us', $string)) {
        return $string;
    }

    // Attempt to strip the bad chars if requested (not recommended)
    if ($strip && function_exists('iconv')) {
        return iconv('utf-8', 'utf-8', $string);
    }

    return '';
}

function _specialchars($string, $quote_style = ENT_NOQUOTES, $charset = false, $double_encode = false)
{
    $string = (string)$string;

    if (0 === strlen($string)) {
        return '';
    }

    // Don't bother if there are no specialchars - saves some processing
    if (!preg_match('/[&<>"\']/', $string)) {
        return $string;
    }

    // Account for the previous behaviour of the function when the $quote_style is not an accepted value
    if (empty($quote_style)) {
        $quote_style = ENT_NOQUOTES;
    } elseif (!in_array($quote_style, array(0, 2, 3, 'single', 'double'), true)) {
        $quote_style = ENT_QUOTES;
    }

    // Store the site charset as a static to avoid multiple calls to wp_load_alloptions()
    if (!$charset) {
        static $_charset = null;
        if (!isset($_charset)) {
            $alloptions = [];
            $_charset = isset($alloptions['blog_charset']) ? $alloptions['blog_charset'] : '';
        }
        $charset = $_charset;
    }

    if (in_array($charset, array('utf8', 'utf-8', 'UTF8'))) {
        $charset = 'UTF-8';
    }

    $_quote_style = $quote_style;

    if ($quote_style === 'double') {
        $quote_style = ENT_COMPAT;
        $_quote_style = ENT_COMPAT;
    } elseif ($quote_style === 'single') {
        $quote_style = ENT_NOQUOTES;
    }

    if (!$double_encode) {
        // Guarantee every &entity; is valid, convert &garbage; into &amp;garbage;
        // This is required for PHP < 5.4.0 because ENT_HTML401 flag is unavailable.
        $string = kses_normalize_entities($string);
    }

    $string = @htmlspecialchars($string, $quote_style, $charset, $double_encode);

    // Back-compat.
    if ('single' === $_quote_style) {
        $string = str_replace("'", '&#039;', $string);
    }

    return $string;
}

function kses_normalize_entities($string)
{
    // Disarm all entities by converting & to &amp;
    $string = str_replace('&', '&amp;', $string);

    // Change back the allowed entities in our entity whitelist
    $string = preg_replace_callback('/&amp;([A-Za-z]{2,8}[0-9]{0,2});/', 'kses_named_entities', $string);
    $string = preg_replace_callback('/&amp;#(0*[0-9]{1,7});/', 'kses_normalize_entities2', $string);
    $string = preg_replace_callback('/&amp;#[Xx](0*[0-9A-Fa-f]{1,6});/', 'kses_normalize_entities3', $string);

    return $string;
}

function kses_named_entities($matches)
{
    global $allowedentitynames;

    if (empty($matches[1]) && is_array($matches[1])) {
        return '';
    }

    $i = $matches[1];

    if (is_array($allowedentitynames)) {
        return (!in_array($i, $allowedentitynames)) ? "&amp;$i;" : "&$i;";
    } else {
        return '';
    }
}

function kses_normalize_entities2($matches)
{
    if (empty($matches[1])) {
        return '';
    }

    $i = $matches[1];
    if (valid_unicode($i)) {
        $i = str_pad(ltrim($i, '0'), 3, '0', STR_PAD_LEFT);
        $i = "&#$i;";
    } else {
        $i = "&amp;#$i;";
    }

    return $i;
}

function kses_normalize_entities3($matches)
{
    if (empty($matches[1])) {
        return '';
    }

    $hexchars = $matches[1];
    return (!valid_unicode(hexdec($hexchars))) ? "&amp;#x$hexchars;" : '&#x' . ltrim($hexchars, '0') . ';';
}

function valid_unicode($i)
{
    return ($i == 0x9 || $i == 0xa || $i == 0xd ||
        ($i >= 0x20 && $i <= 0xd7ff) ||
        ($i >= 0xe000 && $i <= 0xfffd) ||
        ($i >= 0x10000 && $i <= 0x10ffff));
}

function get_comment_number($post_id, $type = 'posts')
{
    $comment = new Comment();
    $comment_count = $comment->getCommentCountByPostID($post_id, $type);
    return $comment_count;
}

function d($arr)
{
    echo '<pre style="background: #000; padding: 20px; color: #fff;">';
    print_r($arr);
    echo '</pre>';
}


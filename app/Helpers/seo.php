<?php
function enable_seo()
{
    $enable = get_option('enable_seo', 'off');
    return $enable == 'on' ? true : false;
}

function seo_output()
{
    if (!enable_seo()) {
        return;
    }
    global $post;
    if (is_null($post)) {
        $page_name = Route::currentRouteName();
        if (!empty($page_name)) {
            $seo_item = get_seo_item_by_post_id($page_name, 'seo_page');
        }
    } else {
        $seo_item = get_seo_item_by_post_id($post->post_id, $post->post_type);
    }
    if (is_null($seo_item)) {
        return;
    }
    $current_language = get_current_language();
    ?>
    <meta name="description"
          content="<?php echo seo_encode(get_translate($seo_item->seo_title, $current_language), $current_language, $post) ?>"/>
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
    <?php if (!is_null($post)) { ?>
        <link rel="canonical" href="<?php echo get_the_permalink($post->post_id, $post->post_slug, $post->post_type) ?>"/>
    <?php } elseif($seo_item->post_id =='home-page') { ?>
        <link rel="canonical" href="<?php echo url('/'); ?>"/>
    <?php }else{ ?>
        <link rel="canonical" href="<?php echo url($seo_item->post_id); ?>"/>
    <?php } ?>
    <!--  Facebook  -->
    <meta property="og:locale" content="<?php echo str_replace('_', '-', app()->getLocale()) ?>"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title"
          content="<?php echo seo_encode(get_translate($seo_item->facebook_title, $current_language), $current_language, $post) ?>"/>
    <meta property="og:description"
          content="<?php echo seo_encode(get_translate($seo_item->facebook_description, $current_language), $current_language, $post) ?>"/>
    <?php if (!is_null($post)) { ?>
        <meta property="og:url"
              content="<?php echo get_the_permalink($post->post_id, $post->post_slug, $post->post_type) ?>"/>
    <?php } elseif($seo_item->post_id =='home-page') { ?>
        <link rel="canonical" href="<?php echo url('/'); ?>"/>
    <?php } else { ?>
        <link rel="canonical" href="<?php echo url($seo_item->post_id); ?>"/>
    <?php } ?>
    <meta property="og:site_name" content="<?php echo e(get_site_name()) ?>"/>
    <?php if (!is_null($post)) { ?>
        <meta property="article:published_time" content="<?php echo date('c', $post->created_at) ?>"/>
    <?php } else { ?>
        <meta property="article:published_time" content="<?php echo date('c', $seo_item->created_at) ?>"/>
    <?php } ?>
    <meta property="og:image" content="<?php echo get_attachment_url($seo_item->facebook_image) ?>"/>

    <!--  Twitter  -->
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title"
          content="<?php echo seo_encode(get_translate($seo_item->twitter_title, $current_language), $current_language, $post) ?>"/>
    <meta name="twitter:description"
          content="<?php echo seo_encode(get_translate($seo_item->twitter_description, $current_language), $current_language, $post) ?>"/>
    <meta name="twitter:image" content="<?php echo get_attachment_url($seo_item->twitter_image) ?>"/>
    <meta name="twitter:label1" content="<?php echo __('Written by'); ?>">
    <?php if (!is_null($post)) { ?>
        <meta name="twitter:data1" content="<?php echo get_username($post->author) ?>">
    <?php } else { ?>
        <meta name="twitter:data1" content="<?php echo get_username(get_admin_user()->getUserId()) ?>">
    <?php } ?>
    <?php
}

function get_seo_item_by_post_id($post_id, $post_type = 'post')
{
    $seo = new \App\Models\Seo();

    return $seo->getByPostId($post_id, $post_type);
}

function get_seo_keys()
{
    return [
        'seo_title' => true,
        'seo_description' => true,
        'facebook_image' => false,
        'facebook_title' => true,
        'facebook_description' => true,
        'twitter_image' => false,
        'twitter_title' => true,
        'twitter_description' => true,
    ];
}

function seo_render(object $seo, $language_code, object $post)
{
    $seo_keys = get_seo_keys();
    $data = [];
    $is_multi_language = is_multi_language();

    foreach ($seo_keys as $seo_key => $translation) {
        if (isset($seo->$seo_key)) {
            if (strpos($seo_key, 'image') !== FALSE) {
                $data[$seo_key] = '<img src="' . get_attachment_url($seo->$seo_key, [500, 350]) . '" alt=' . __('Thumbnail') . '/>';
            } else {
                if ($is_multi_language) {
                    if (!$translation) {
                        $data[$seo_key] = $seo->$seo_key;
                    } else {
                        $data[$seo_key . '_' . $language_code] = seo_encode(get_translate($seo->$seo_key, $language_code), $language_code, $post);
                    }
                } else {
                    $data[$seo_key] = $seo->$seo_key;
                }
            }
        } else {
            if (strpos($seo_key, 'image') !== FALSE) {
                $thumbnail = $post->thumbnail_id;
                if (!empty($thumbnail)) {
                    $data[$seo_key] = '<img src="' . get_attachment_url($thumbnail, [500, 350]) . '" alt=' . __('Thumbnail') . '/>';
                } else {
                    $data[$seo_key] = '';
                }
            } else {
                $data[$seo_key] = '';
            }

        }
    }

    return $data;
}

function seo_encode(string $seo_text, $language_code, $post)
{
    $encode = [
        '{{id}}' => $post->post_id ?? '',
        '{{title}}' => isset($post->post_title) ? get_translate($post->post_title, $language_code) : '',
        '{{description}}' => isset($post->post_description) ? get_translate($post->post_description, $language_code) : '',
        '{{separator}}' => '-',
        '{{site-title}}' => get_translate(get_option('site_name'), $language_code),
        '{{site-description}}' => get_translate(get_option('site_description'), $language_code),
    ];

    return str_replace(array_keys($encode), array_values($encode), $seo_text);
}

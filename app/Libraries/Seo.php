<?php

use  Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class Seo
{
    public function __construct()
    {
        add_filter('awebooking_post_title', [$this, '_set_seo_title'], 10, 3);
        add_action('awebooking_dashboard_menu_item_advanced_after', [$this, '_add_seo_menu']);
    }

    public function _add_seo_menu()
    {

        if (is_admin() && enable_seo()) {
            ?>
            <li>
                <a href="<?php echo esc_url(dashboard_url('seo-tools')) ?>">
                    <?php echo get_icon('001_seo', '#555', '20px') ?>
                    <?php echo __('Seo Tools') ?>
                </a>
            </li>
            <?php
        }
    }

    public function _set_seo_title($title, $params, $post)
    {
        if (enable_seo() && is_singular()) {
            $seo_item = get_seo_item_by_post_id($post->post_id, $post->post_type);
            if ($seo_item && !empty($seo_item->seo_title)) {
                if (is_multi_language()) {
                    $language_code = get_current_language();
                    $title = seo_encode(get_translate($seo_item->seo_title, $language_code), $language_code, $post);
                } else {
                    $title = $seo_item->seo_title;
                }
            }
        }

        return $title;
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

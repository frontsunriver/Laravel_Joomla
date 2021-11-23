<?php

use App\Models\Home;

function count_home_in_home_type($home_type_id)
{
    $homeModel = new Home();
    return $homeModel->countHomeInHomeType($home_type_id);
}

function render_home_comment_list($comments)
{
    echo '<ul class="home-review-list">';
    foreach ($comments as $k => $v) {
        ?>
        <li id="comment-<?php echo esc_attr($v->comment_id); ?>" class="comment comment-home odd alt thread-odd thread-alt depth-1">
            <div id="div-comment-<?php echo esc_attr($v->comment_id) ?>" class="article comment  clearfix"
                 inline_comment="comment">
                <div class="comment-item-head">
                    <div class="media">
                        <div class="media-left">
                            <img alt="" src="<?php echo get_user_avatar($v->comment_author) ?>"
                                 class="avatar avatar-50 photo avatar-default" height="50" width="50">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <?php
                                if (is_user_logged_in()) {
                                    echo esc_html(get_username($v->comment_author));
                                } else {
                                    echo esc_html($v->comment_name);
                                }
                                ?>
                            </h4>
                            <div class="date"><?php echo esc_html(date(hh_date_format(), $v->created_at)) ?></div>
                        </div>
                    </div>
                </div>
                <div class="comment-item-body">
                    <div class="comment-content">
                        <p class="comment-title"><?php echo esc_html($v->comment_title); ?></p>
                        <?php review_rating_star($v->comment_rate); ?>
                        <p><?php echo esc_html($v->comment_content); ?></p>
                    </div>
                </div>
            </div>
        </li>
        <?php
    }
    echo '</ul>';
}

function get_home_search_page($params = '')
{
    return url(Config::get('awebooking.post_types')['home']['search_slug'] . '/' . $params);
}

function get_home_unit($home_object)
{
    if ($home_object->booking_type == 'per_night') {
        return __('night');
    } elseif ($home_object->booking_type == 'per_hour') {
        return __('hour');
    }

    return '';
}

function get_home_permalink($home_id, $home_slug = '')
{
    return get_the_permalink($home_id, $home_slug, 'home');
}

function has_home_thumbnail($home_id)
{
    if (!is_object($home_id)) {
        $home_object = new Home();
        $home_id = $home_object->getById($home_id);
    }
    if(is_object($home_id)){
        return isset($home_id->thumbnail_id) && $home_id->thumbnail_id;
    }
    return false;
}

function get_home_thumbnail_id($home_id)
{
    if(!is_object($home_id)){
        $home_object = new Home();
        $home_id = $home_object->getById($home_id);
    }
    if(is_object($home_id)){

        return (isset($home_id->thumbnail_id) && $home_id->thumbnail_id) ? $home_id->thumbnail_id : false;
    }

    return false;
}

function get_home_terms_filter()
{
    $res = [];
    $filter_type = [
        'home-type' => __('Home Type'),
        'home-amenity' => __('Home Amenity'),
        'home-facilities' => __('Home Facilities Fields'),
        // 'home-distance' => __('Home Distances')
    ];
    foreach ($filter_type as $k => $v) {
        $res[$k] = [
            'label' => $v,
            'items' => get_terms_search($k)
        ];
    }
    return $res;
}

<?php

use App\Models\Experience;

function get_experience_terms_filter()
{
    $res = [];
    $filter_type = [
        'experience-languages' => __('Experience Languages'),
    ];
    foreach ($filter_type as $k => $v) {
        $res[$k] = [
            'label' => $v,
            'items' => get_terms($k)
        ];
    }
    return $res;
}

function get_experience_search_page($params = '')
{
    return url(Config::get('awebooking.post_types')['experience']['search_slug'] . '/' . $params);
}


function count_experience_in_experience_type($experience_type_id)
{
    $experienceModel = new Experience();
    return $experienceModel->countExperienceInExperienceType($experience_type_id);
}

function render_experience_comment_list($comments)
{
    echo '<ul class="experience-review-list">';
    foreach ($comments as $k => $v) {
        ?>
        <li id="comment-<?php echo esc_attr($v->comment_id); ?>" class="comment comment-experience odd alt thread-odd thread-alt depth-1">
            <div id="div-comment-<?php echo esc_attr($v->comment_id) ?>" class="article comment  clearfix"
                 inline_comment="comment">
                <div class="comment-item-head">
                    <div class="media">
                        <div class="media-left">
                            <img alt="<?php echo e(__('Avatar')); ?>"
                                 src="<?php echo get_user_avatar($v->comment_author) ?>"
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


function get_experience_permalink($experience_id, $experience_slug = '')
{
    return get_the_permalink($experience_id, $experience_slug, 'experience');
}

function has_experience_thumbnail($experience_id)
{
    if (!is_object($experience_id)) {
        $experience_object = new Experience();
        $experience_id = $experience_object->getById($experience_id);
    }
    if(is_object($experience_id)){
        return isset($experience_id->thumbnail_id) && $experience_id->thumbnail_id;
    }
    return false;

}

function get_experience_unit($post)
{
    return __('people');
}

function get_experience_thumbnail_id($experience_id)
{
    if(!is_object($experience_id)){
        $experience_object = new Experience();
        $experience_id = $experience_object->getById($experience_id);
    }
    if(is_object($experience_id)){

        return (isset($experience_id->thumbnail_id) && $experience_id->thumbnail_id) ? $experience_id->thumbnail_id : false;
    }

    return false;
}

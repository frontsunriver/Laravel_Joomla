<?php
global $post;
?>
@include('frontend.components.header')
<?php
$banner_bg = get_attachment_url($post->thumbnail_id);
$comments = get_comment_list($post->post_id, [
    'number' => comments_per_page(),
    'page' => request()->get('comment_page', 1)
]);
?>
<div class="page-archive pb-4">
    <div class="banner" style="background: #eee url('{{$banner_bg}}') center center/cover no-repeat;">
    </div>
    <div class="container">
        <div class="d-none d-lg-block">
            @include('frontend.components.breadcrumb', ['currentPage' => get_translate($post->post_title)])
        </div>
        <div class="row">
            <div class="col-12 col-lg-9">
                <div class="page-content">
                    <h1 class="title">{{ get_translate($post->post_title) }}</h1>
                    <div class="page-content-inner">
                        <!-- Meta -->
                        <ul class="post-meta">
                            <li>
                                {{ sprintf(__('By %s'), get_username($post->author)) }}
                            </li>
                            <?php if(!isset($is_blog_page)){ ?>
                            <li>
                                {{ date(hh_date_format(), $post->created_at) }}
                            </li>
                            <?php } ?>
                            <?php
                            $categories = get_category($post->post_id);
                            if ( !empty($categories) ) {
                            ?>
                            <li>
                                {{__('on')}}
                                <?php
                                $arr_cate = [];
                                foreach ($categories as $k => $v) {
                                    array_push($arr_cate, '<a href="' . get_term_link($v->term_name) . '">' . esc_html(get_translate($v->term_title)) . '</a>');
                                }
                                echo implode(',', $arr_cate);
                                ?>
                            </li>
                            <?php
                            }
                            if(!isset($is_blog_page)){
                            ?>
                            <li>
                                <?php
                                if (!empty($comments['count'])) {
                                    $comment_count = get_comment_number($post->post_id);
                                    if ($comment_count == 1) {
                                        echo sprintf(__('%s comment'), esc_attr($comment_count));
                                    } else {
                                        echo sprintf(__('%s comments'), esc_attr($comment_count));
                                    }
                                } else {
                                    echo sprintf(__('%s comments'), esc_attr($comments['count']));
                                }
                                ?>
                            </li>
                            <?php } ?>
                        </ul>

                    <?php echo balanceTags(get_translate($post->post_content)); ?>
                    <!-- Tags -->
                        <?php
                        $tags = get_tag($post->post_id);
                        if(!empty($tags)){
                        ?>
                        <div class="post-tags">
                            <p class="tag-title">{{__('Tags')}}</p>
                            <ul>
                                <?php
                                foreach ($tags as $k => $v) {
                                    echo '<li><a href="' . get_term_link($v->term_name, 'tag') . '">' . esc_html(get_translate($v->term_title)) . '</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <?php
                        }
                        ?>
                        <?php
                        $url = get_the_permalink($post->post_id, $post->post_slug, 'post');
                        $img = get_attachment_url(get_translate($post->thumbnail_id));
                        $desc = $post->post_title;
                        $share = [
                            'facebook' => [
                                'url' => $url
                            ],
                            'twitter' => [
                                'url' => $url
                            ],
                            'pinterest' => [
                                'url' => $url,
                                'img' => $img,
                                'description' => $desc
                            ]
                        ];
                        ?>
                        @include('frontend.components.share')
                        @include('frontend.blog.comment')
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="page-sidebar">
                    @include('frontend.components.sidebar', ['type' => 'post'])
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.components.footer')

@include('frontend.components.header')
<?php
$banner_bg = get_blog_image_url();
$page_title = __('Blog');
$bread_title = __('Blog');
if (isset($term)) {
    $page_title = $bread_title = $term['taxonomy'];
    $term_obj = get_term_by('name', $term['term_slug']);
    if ($term_obj != null) {
        $page_title = $page_title . ': ' . get_translate($term_obj->term_title);
    }
}
?>
<div class="page-archive blog-page pb-4">
    <div class="banner" style="background: #eee url('{{ $banner_bg }}') center center/cover no-repeat;">
    </div>
    <div class="container">
        <div class="d-none d-lg-block">@include('frontend.components.breadcrumb', ['currentPage' => $bread_title])</div>
        <div class="row">
            <div class="col-12 col-lg-9">
                <div class="page-content">
                    <h1 class="page-title">{{ $page_title }}</h1>
                    @if(isset($term) && !is_null($term_obj))
                        <p class="term-description">
                            {{ get_translate($term_obj->term_description) }}
                        </p>
                    @endif
                    @if($posts['total'] > 0)
                        <div class="page-content-inner">
                            <div class="row">
                                @foreach($posts['results'] as $post)
                                    <?php $is_blog_page = true ?>
                                    <div class="col-md-6">
                                        <div class="post-item">
                                            <div class="post-thumbnail">
                                                <p class="post-date">{{ date(hh_date_format(), $post->created_at) }}</p>
                                                <a href="{{ get_the_permalink($post->post_id, $post->post_slug, 'post') }}">
                                                    <?php
                                                    $thumb_id = $post->thumbnail_id;
                                                    $thumb_src = get_attachment_url($thumb_id, [550, 270], true);
                                                    ?>
                                                    <img src="{{ $thumb_src }}"
                                                         alt="{{ get_attachment_alt($thumb_id) }}" class="img-fluid"/>
                                                </a>
                                            </div>
                                            <h3 class="post-title"><a
                                                    href="{{ get_the_permalink($post->post_id, $post->post_slug, 'post') }}">
                                                    {{ get_translate($post->post_title) }}</a></h3>

                                            <ul class="post-meta">
                                                <li>
                                                    {{ sprintf(__('By %s'), get_username($post->author)) }}
                                                </li>
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
                                                ?>
                                            </ul>

                                            <a href="{{ get_the_permalink($post->post_id, $post->post_slug, 'post') }}"
                                               class="readmore">
                                                {{__('Read more')}}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <?php
                            frontend_pagination([
                                'total' => $posts['total'],
                                'query_page' => false,
                                'force_query_false' => -1,
                                'slug' => isset($slug) ? $slug : '',
                                'posts_per_page' => posts_per_page()
                            ]);
                            ?>
                        </div>
                    @else
                        <p>{{__('No posts found')}}</p>
                    @endif
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

<?php
global $post;
?>
@include('frontend.components.header')
<?php $banner_bg = get_attachment_url(get_translate($post->thumbnail_id)); ?>
<div class="page-archive pb-4">
    <div class="banner" style="background: #eee url('{{ $banner_bg }}') center center/cover no-repeat;"></div>
    <div class="container">
        @include('frontend.components.breadcrumb', ['currentPage' => get_translate($post->post_title)])
        <div class="row">
            <div class="col-12 col-lg-9">
                <div class="page-content">
                    <h1 class="title">{{ get_translate($post->post_title) }}</h1>
                    <div class="page-content-inner">
                        <?php echo balanceTags(get_translate($post->post_content)); ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="page-sidebar">
                    @include('frontend.components.sidebar', ['type' => 'page'])
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.components.footer')

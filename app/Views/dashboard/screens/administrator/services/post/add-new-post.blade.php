@include('dashboard.components.header');
<?php
enqueue_style('confirm-css');
enqueue_script('confirm-js');
enqueue_script('nice-select-js');
enqueue_style('nice-select-css');
?>
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            @include('dashboard.components.breadcrumb', ['heading' => __('New Post')])
            @if (!$newPost)
                <div class="card-box">
                    <div class="alert alert-danger">{{__('Can not create Post')}}</div>
                </div>
            @else
                <?php
                $postObject = get_post($newPost, 'post', true);
                ?>
                <form class="form form-action relative form-translation" action="{{ dashboard_url('edit-post') }}"
                      data-validation-id="form-new-post"
                      method="post" data-reload-time="1000">
                <?php show_lang_section(); ?>
                <!-- @include('common.loading') -->
                    <input type="hidden" name="postID" value="{{ $newPost }}">
                    <input type="hidden" name="postEncrypt" value="{{ hh_encrypt($newPost) }}">
                    <input type="hidden" name="action" value="add-new">
                    <input type="hidden" name="current_language_switcher" value="{{ get_current_language() }}">
                    <div class="row">
                        <div class="col-12 col-md-8 order-md-4">
                            <div class="card-box">
                                <h4 class="page-title">
                                    {{ __('New Post') }}
                                </h4>
                                <hr/>
                                {!!  \ThemeOptions::renderPageMeta('post_settings.content', $newPost, true, 'all-post', 'post') !!}
                            </div>
                        </div>
                        <div class="col-12 col-md-4 order-md-8">
                            <div class="card-box">
                                <div
                                    class="d-flex d-md-block d-xl-flex align-items-center mb-2 justify-content-between">
                                    <div class="d-flex align-items-center form-xs">
                                        <label for="hh-page-status" class="mb-0">{{__('Status')}} &nbsp;</label>
                                        <select class="form-control min-w-100" id="hh-page-status" name="post_status"
                                                data-plugin="customselect">
                                            <option
                                                value="publish" {{ $postObject->status == 'publish' ? 'selected' : ''  }}>{{__('Publish')}}</option>
                                            <option
                                                value="draft" {{ $postObject->status == 'draft' ? 'selected' : ''  }}>{{__('Draft')}}</option>
                                        </select>
                                    </div>

                                    <button class="btn btn-success waves-effect waves-light mb-0 mt-md-2 mt-xl-0"
                                            type="submit">{{__('Publish')}}</button>
                                </div>
                                {!!  \ThemeOptions::renderPageMeta('post_settings.sidebar', $newPost, true, 'all-post', 'post') !!}
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-12 col-lg-8">
                        @include("dashboard.seo.index", ['serviceObject' => $postObject])
                    </div>
                </div>
            @endif
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer');

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
            @include('dashboard.components.breadcrumb', ['heading' => __('Edit Page')])
            @if (!$newPage)
                <div class="card-box">
                    <div class="alert alert-danger">{{__('Can not edit Page')}}</div>
                </div>
            @else
                <?php
                $pageObject = get_post($newPage, 'page', true);
                ?>
                <form class="form form-action relative form-translation" action="{{ dashboard_url('edit-page') }}"
                      data-validation-id="form-edit-page"
                      method="post" data-reload-time="1000">
                <?php show_lang_section(); ?>
                <!-- @include('common.loading') -->
                    <input type="hidden" name="postID" value="{{ $newPage }}">
                    <input type="hidden" name="postEncrypt" value="{{ hh_encrypt($newPage) }}">
                    <input type="hidden" name="current_language_switcher" value="{{ get_current_language() }}">
                    <div class="row">
                        <div class="col-12 col-md-8 order-md-4">
                            <div class="card-box">
                                <h4 class="page-title">
                                    {{ __('Edit Page') }}
                                    <a target="_blank"
                                       href="{{ get_the_permalink($newPage, $pageObject->post_slug, 'page') }}"
                                       class="right"><small>{{__('View page')}}</small></a>
                                </h4>
                                <hr/>
                                {!!  \ThemeOptions::renderPageMeta('page_settings.content', $newPage, false, 'all-page') !!}
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
                                                value="publish" {{ $pageObject->status == 'publish' ? 'selected' : ''  }}>{{__('Publish')}}</option>
                                            <option
                                                value="draft" {{ $pageObject->status == 'draft' ? 'selected' : ''  }}>{{__('Draft')}}</option>
                                        </select>
                                    </div>

                                    <button class="btn btn-success waves-effect waves-light mb-0 mt-md-2 mt-xl-0"
                                            type="submit">{{__('Publish')}}</button>
                                </div>
                                <?php
                                $data = [
                                    'serviceID' => $newPage,
                                    'serviceEncrypt' => hh_encrypt($newPage),
                                    'type' => 'in-page'
                                ];
                                ?>
                                <a class="hh-link-action hh-link-delete-page d-inline-flex align-items-center a-red"
                                   data-action="{{ dashboard_url('delete-page-item') }}"
                                   data-parent=""
                                   data-params="{{ base64_encode(json_encode($data)) }}"
                                   data-confirm="yes"
                                   data-is-delete="true"
                                   data-confirm-title="{{__('Confirm Delete')}}"
                                   data-confirm-question="{{__('Are you sure want to delete this page?')}}"
                                   data-confirm-button="{{__('Delete it!')}}"
                                   href="javascript: void(0)">
                                    <i class="ti-trash mr-1"></i>
                                    {{__('Delete this page')}}</a>
                                <hr/>
                                {!!  \ThemeOptions::renderPageMeta('page_settings.sidebar', $newPage, false, 'all-page') !!}
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-12 col-lg-8">
                        @include("dashboard.seo.index", ['serviceObject' => $pageObject])
                    </div>
                </div>
            @endif
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer');

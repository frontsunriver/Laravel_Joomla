@include('dashboard.components.header')
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            {{--Start Content--}}
            @include('dashboard.components.breadcrumb', ['heading' => __('Add new Home')])
            @if (!$newHome)
                <div class="card-box">
                    <div class="alert alert-danger">{{__('Can not create new Home')}}</div>
                </div>
            @else
                <?php
                $serviceObject = get_post($newHome, 'home', true);
                ?>
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="card-box">
                            {!!  \ThemeOptions::renderMeta('home_settings', $serviceObject , true, dashboard_url('post-new-home'), 'my-home') !!}
                        </div>
                    </div>
                    <div class="d-none d-lg-block col-lg-4">
                        @include('dashboard.components.services.home.home_preview')
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-8">
                        @include("dashboard.seo.index", ['serviceObject' => $serviceObject])
                    </div>
                </div>
            @endif
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')

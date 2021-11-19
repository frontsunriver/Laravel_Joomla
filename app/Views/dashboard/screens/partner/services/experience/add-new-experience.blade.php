@include('dashboard.components.header')
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            {{--Start Content--}}
            @include('dashboard.components.breadcrumb', ['heading' => __('Add new Experience')])
            @if (!$newExperience)
                <div class="card-box">
                    <div class="alert alert-danger">{{__('Can not create new Experience')}}</div>
                </div>
            @else
                <?php
                $serviceObject = get_post($newExperience, 'experience', true);
                ?>
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="card-box">
                            {!!  \ThemeOptions::renderMeta('experience_settings', $serviceObject , true, dashboard_url('post-new-experience'), 'my-experience') !!}
                        </div>
                    </div>
                    <div class="d-none d-lg-block col-lg-4">
                        @include('dashboard.components.services.experience.experience_preview')
                    </div>
                </div>
            @endif
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')

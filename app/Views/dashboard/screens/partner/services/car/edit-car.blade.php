@include('dashboard.components.header')
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            {{--Start Content--}}
            @include('dashboard.components.breadcrumb', ['heading' => __('Edit Car')])
            @if (!$newCar)
                <div class="card-box">
                    <div class="alert alert-danger">{{__('This Car is invalid')}}</div>
                </div>
            @else
                <?php
                $serviceObject = get_post($newCar, 'car', true);
                ?>
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="card-box">
                            {!! \ThemeOptions::renderMeta('car_settings', $serviceObject, false, dashboard_url('post-new-car'), 'my-car') !!}
                        </div>
                    </div>
                    <div class="d-none d-lg-block col-lg-4">
                        @include('dashboard.components.services.car.car-preview')
                    </div>
                </div>
            @endif
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')

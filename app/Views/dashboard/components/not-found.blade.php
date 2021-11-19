@include('dashboard.components.header');
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            <div class="card-box">
                <div class="alert alert-warning mb-0">
                    {{__('Page not found')}}
                </div>
            </div>
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer');

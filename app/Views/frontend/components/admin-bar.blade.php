<?php
    global $post;
?>
<div id="admin-bar" class="admin-bar">
    <div class="admin-bar__logo">
        <a href="{{dashboard_url('/')}}"><span
                class="mr-1">{!! get_icon('001_dashboard', '#D8D8D8', '18px', '18px') !!}</span>{{__('Dashboard')}}
        </a>
    </div>
    <div class="admin-bar__actions">
        <div class="admin-bar-item admin-bar-action--add-new dropdown">
            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                <span class="icon"><i class="mdi mdi-plus mr-1"></i></span>
                {{__('Add New')}}
            </button>
            <div class="dropdown-menu dropdown-menu-right">
            @if(is_admin())
                <!-- item-->
                    <a href="{{ dashboard_url('add-new-post') }}" class="dropdown-item">
                        <span>{{__('New Post')}}</span>
                    </a>
                    <!-- item-->
                    <a href="{{ dashboard_url('add-new-page') }}" class="dropdown-item">
                        <span>{{__('New Page')}}</span>
                    </a>
                    <div class="dropdown-divider"></div>
            @endif
            <!-- item-->
                @if(is_enable_service('home'))
                    <a href="{{ dashboard_url('add-new-home') }}" class="dropdown-item">
                        <span>{{__('New Home')}}</span>
                    </a>
                @endif
            <!-- item-->
                @if(is_enable_service('experience'))
                    <a href="{{ dashboard_url('add-new-experience') }}" class="dropdown-item">
                        <span>{{__('New Experience')}}</span>
                    </a>
                @endif
            <!-- item-->
                @if(is_enable_service('car'))
                    <a href="{{ dashboard_url('add-new-car') }}" class="dropdown-item">
                        <span>{{__('New Car')}}</span>
                    </a>
                @endif
                @if(is_admin())
                    <div class="dropdown-divider"></div>
                    <!-- item-->
                    <a href="{{ dashboard_url('coupon') }}" class="dropdown-item">
                        <span>{{__('New Coupon')}}</span>
                    </a>
                @endif
            </div>
        </div>
        @if(user_can_edit_service($post) && get_edit_link())
            <div class="admin-bar-item admin-bar-action--edit">
                <a class="link" href="{{get_edit_link()}}">
                    <span class="icon mr-1"><i class="ti-pencil"></i></span>
                    {{__('Edit')}}
                </a>
            </div>
        @endif
        @if(get_option('optimize_site', 'off') == 'on')
            <?php
            $current_route = Route::current();
            $name = $current_route->getName();
            ?>
            <div class="admin-bar-item admin-bar-action--clear-cache d-none d-md-block">
                <a class="link" href="{{url('cache/'. $name. '/'. base64_encode(current_url()))}}">
                    <span class="icon mr-1"><i class="mdi mdi-cached"></i></span>
                    {{__('Clear cache this page')}}
                </a>
            </div>
            <div class="admin-bar-item d-block d-md-none dropdown">
                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <i class="icon-layers"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{url('cache/'. $name. '/'. base64_encode(current_url()))}}">
                        <span class="icon mr-1"><i class="mdi mdi-cached"></i></span>
                        {{__('Clear cache this page')}}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<?php do_action('init'); ?>
<?php do_action('frontend_init'); ?>
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <?php
    $favicon = get_option('favicon');
    $favicon_url = get_attachment_url($favicon);
    ?>
    <link rel="shortcut icon" type="image/png" href="{{ $favicon_url }}"/>
    <title>{{ page_title() }}</title>
    {{ seo_output() }}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;1,400&display=swap"
          rel="stylesheet">
    <?php do_action('header'); ?>
    <?php do_action('init_header'); ?>
    <?php do_action('init_frontend_header'); ?>
    <?php
    $body_class = isset($bodyClass) ? $bodyClass : '';
    if (is_user_logged_in() && (is_admin() || is_partner())) {
        $body_class .= ' has-admin-bar';
    }
    ?>
</head>
<body class="awe-booking {{is_rtl()? 'rtl': ''}} {{ $body_class }}">
<?php do_action('after_body_frontend'); ?>
<nav id="mobile-navigation" class="main-navigation mobile-natigation d-lg-none"
     aria-label="{{__('Top Menu')}}">
    <div class="menu-primary-container">
        <?php
        if (has_nav_primary()) {
            get_nav([
                'location' => 'primary',
                'walker' => 'main-mobile'
            ]);
        }
        ?>
    </div>
</nav><!-- #site-navigation -->
@include('common.loading', ['class' => 'page-loading'])
@if(!is_user_logged_in())
    <div id="hh-login-modal" class="modal fade modal-no-footer" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-uppercase">{{__('Login')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <form id="hh-login-form" class="form form-sm form-action" action="{{ url('auth/login') }}"
                          data-validation-id="form-login"
                          data-reload-time="1500"
                          method="post">
                        @include('common.loading')
                        <div class="form-group mb-3">
                            <label for="email-login-form">{{__('Email address')}}</label>
                            <input class="form-control has-validation" data-validation="required" type="text"
                                   id="email-login-form" name="email" placeholder="{{__('Enter your email')}}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="password-login-form">{{__('Password')}}</label>
                            <input class="form-control has-validation" data-validation="required|min:6:ms"
                                   type="password" id="password-login-form" name="password"
                                   placeholder="{{__('Enter your password')}}">
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkbox-signin-login-form"
                                       checked>
                                <label class="custom-control-label"
                                       for="checkbox-signin-login-form">{{__('Remember me')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-center">
                            {!! referer_field(false) !!}
                            <button class="btn btn-primary btn-block text-uppercase"
                                    type="submit"> {{__('Log In')}}</button>
                        </div>
                        <div class="form-message"></div>
                        @if(has_social_login())
                            <div class="text-center">
                                <p class="mt-3 text-muted">{{__('Log in with')}}</p>
                                <ul class="social-list list-inline mt-3 mb-0">
                                    @if(social_enable('facebook'))
                                        <li class="list-inline-item">
                                            <a href="{{ FacebookLogin::get_inst()->getLoginUrl() }}"
                                               class="social-list-item border-primary text-primary"><i
                                                    class="mdi mdi-facebook"></i></a>
                                        </li>
                                    @endif
                                    @if(social_enable('google'))
                                        <li class="list-inline-item">
                                            <a href="{{ GoogleLogin::get_inst()->getLoginUrl() }}"
                                               class="social-list-item border-danger text-danger"><i
                                                    class="mdi mdi-google"></i></a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                        <div class="mt-3 d-sm-flex align-items-center justify-content-between">
                            <p>{{__('Don\'t have an account?')}}
                                <a href="javascript: void(0)" data-toggle="modal" data-target="#hh-register-modal"
                                   class="font-weight-bold">{{__('Sign Up')}}</a>
                            </p>
                            <p>
                                <a href="javascript: void(0)" data-toggle="modal" data-target="#hh-fogot-password-modal"
                                   class="font-weight-bold">{{__('Reset Password')}}</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- /.modal -->
    <div id="hh-register-modal" class="modal fade modal-no-footer" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-uppercase">{{__('Sign Up')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <form id="hh-sign-up-form" action="{{ url('auth/sign-up') }}" method="post" data-reload-time="1500"
                          data-validation-id="form-sign-up"
                          class="form form-action">
                        @include('common.loading')
                        <div class="form-group">
                            <label for="first-name-reg-form">{{__('First Name')}}</label>
                            <input class="form-control" type="text" id="first-name-reg-form" name="first_name"
                                   placeholder="{{__('First Name')}}">
                        </div>
                        <div class="form-group">
                            <label for="last-name-reg-form">{{__('Last Name')}}</label>
                            <input class="form-control" type="text" id="last-name-reg-form" name="last_name"
                                   placeholder="{{__('Last Name')}}">
                        </div>
                        <div class="form-group">
                            <label for="email-address-reg-form">{{__('Email address')}}</label>
                            <input class="form-control has-validation" data-validation="required|email" type="email"
                                   id="email-address-reg-form" name="email" placeholder="{{__('Email')}}">
                        </div>
                        <div class="form-group">
                            <label for="password-reg-form">{{__('Password')}}</label>
                            <input class="form-control has-validation" data-validation="required|min:6:ms"
                                   name="password" type="password" id="password-reg-form"
                                   placeholder="{{__('Password')}}">
                        </div>
                        <div class="form-group">
                            <div class="checkbox checkbox-success">
                                <input type="checkbox" id="reg-term-condition" name="term_condition" value="1">
                                <label for="reg-term-condition">
                                    {!! sprintf(__('I accept %s'), '<a class="c-pink" href="'.get_the_permalink(get_option('term_condition_page'), '', 'page').'" class="text-dark">'. __('Terms and Conditions') .'</a>') !!}
                                </label>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-center">
                            <button class="btn btn-primary btn-block text-uppercase"
                                    type="submit"> {{__('Sign Up')}}</button>
                        </div>
                        <div class="form-message"></div>
                        <div class="mt-3 d-sm-flex align-items-center justify-content-between">
                            <p>{{__('Have an account?')}}
                                <a href="javascript: void(0)" data-toggle="modal" data-target="#hh-login-modal"
                                   class="font-weight-bold">{{__('Log In')}}</a>
                            </p>
                        </div>
                    </form>

                    @if(has_social_login())
                        <div class="text-center">
                            <h5 class="mt-3 text-muted">{{__('Sign up using')}}</h5>
                            <ul class="social-list list-inline mt-3 mb-0">
                                @if(social_enable('facebook'))
                                    <li class="list-inline-item">
                                        <a href="{{ FacebookLogin::get_inst()->getLoginUrl() }}"
                                           class="social-list-item border-primary text-primary"><i
                                                class="mdi mdi-facebook"></i></a>
                                    </li>
                                @endif
                                @if(social_enable('google'))
                                    <li class="list-inline-item">
                                        <a href="{{ GoogleLogin::get_inst()->getLoginUrl() }}"
                                           class="social-list-item border-danger text-danger"><i
                                                class="mdi mdi-google"></i></a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div><!-- /.modal -->
    <div id="hh-fogot-password-modal" class="modal fade modal-no-footer" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-uppercase">{{__('Reset Password')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <form id="hh-reset-password-form" action="{{ url('auth/reset-password') }}" method="post"
                          data-validation-id="form-reset-password"
                          data-reload-time="1500"
                          class="form form-action">
                        @include('common.loading')
                        <div class="form-group">
                            <label for="email-address-reset-pass-form">{{__('Email address')}}</label>
                            <input class="form-control has-validation" data-validation="required|email" type="email"
                                   id="email-address-reset-pass-form" name="email" placeholder="{{__('Email')}}">
                        </div>
                        <div class="form-group mb-0 text-center">
                            <button class="btn btn-primary btn-block text-uppercase"
                                    type="submit"> {{__('Reset Password')}}</button>
                        </div>
                        <div class="form-message"></div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- /.modal -->
@endif
<?php
$langs = get_languages(true);
$currencies = list_currencies();
$current_lang = get_current_language();
?>
<div id="hh-modal-global" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header no-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i>
                </button>
            </div>
            <div class="modal-body">
                @if(count($langs))
                    <h4 class="title mt-0">{{__('Select Language')}}</h4>
                    <ul class="list-unstyled list-languages row mt-3">
                        @foreach($langs as $key => $lang)
                            @if($current_lang == $lang['code'])
                                <li class="col-6 col-md-4 mb-3 item current">
                                    <a href="javascript: void(0)">{{__($lang['name'])}}</a>
                                </li>
                            @else
                                <li class="col-6 col-md-4 mb-3 item">
                                    <a href="{{add_query_arg('lang', $lang['code'], current_url())}}">{{$lang['name']}}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
                @if(count($currencies))
                    <h4 class="title mt-0">{{__('Select Currency')}}</h4>
                    <ul class="list-unstyled list-currencies row mt-3">
                        @foreach($currencies as $key => $currency)
                            @if($currency['unit'] == current_currency('unit'))
                                <li class="col-6 col-md-4 mb-3 item current">
                                    <a href="javascript: void(0)">
                                        <span class="symbol">{{$currency['unit']}} - {{$currency['symbol']}}</span>
                                        <span class="name">{{get_translate($currency['name'])}}</span></a>
                                </li>
                            @else
                                <li class="col-6 col-md-4 mb-3 item">
                                    <a href="{{add_query_arg('currency', $currency['unit'], current_url())}}">
                                        <span class="symbol">{{$currency['unit']}} - {{$currency['symbol']}}</span>
                                        <span class="name">{{get_translate($currency['name'])}}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="body-wrapper">
    <?php
    $sticky = get_option('enable_sticky', 'off');
    $classSticky = '';
    if ($sticky == 'on') {
        enqueue_script('sticky-js');
        $classSticky = 'has-sticky';
    }
    ?>
    <header id="header" class="header {{$classSticky}}">
    <span class="d-block d-lg-none" id="toggle-mobile-menu"><span class="top"></span><span class="center"></span><span
            class="bottom"></span></span>
        <a href="{{ url('/') }}" id="logo">
            <?php
            $logo = get_option('logo');
            $logo_url = get_attachment_url($logo);
            ?>
            <img src="{{ $logo_url }}" alt="img-logo" class="img-logo">
        </a>
        <nav id="site-navigation" class="main-navigation d-none d-lg-block"
             aria-label="Primary Menu">
            <div class="menu-prmary-container">
                <?php
                if (has_nav_primary()) {
                    get_nav([
                        'location' => 'primary',
                        'walker' => 'main'
                    ]);
                }
                ?>
            </div>
        </nav><!-- #site-navigation -->
        <div id="right-navigation" class="right-navigation">
            <ul class="list-unstyled topnav-menu mb-0">
                @if(count($langs) || count($currencies))
                    <li class="dropdown global-list">
                        <a class="nav-item nav-item--global" href="javascript: void(0)" data-toggle="modal"
                           data-target="#hh-modal-global">
                            {!! balanceTags(get_icon('global', '#AAAAAA', '18px', '18px')) !!}
                            <i class="ti-angle-down"></i>
                        </a>
                    </li>
                @endif
                @if (is_user_logged_in())
                    <?php
                    $noti = Notifications::get_inst()->countNotificationByUser(get_current_user_id());
                    $user_id = get_current_user_id();
                    $args = [
                        'user_id' => $user_id,
                        'user_encrypt' => hh_encrypt($user_id)
                    ];
                    $userData = get_current_user_data();
                    ?>
                    <li id="hh-dropdown-notification" class="dropdown notification-list"
                        data-action="{{ url('get-notifications') }}"
                        data-params="{{ base64_encode(json_encode($args)) }}">
                        <a class="nav-item dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"
                           role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fe-bell noti-icon"></i>
                            @if($noti['total'])
                                <span
                                    class="badge badge-danger rounded-circle noti-icon-badge">{{ $noti['total'] }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-lg">
                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="m-0">{{__('Notification')}}</h5>
                            </div>
                            <div class="slimscroll noti-scroll">
                                <div class="notification-render">
                                </div>
                            </div>
                            <!-- All-->
                            <a href="{{ dashboard_url('all-notifications') }}"
                               class="dropdown-item text-center text-primary notify-item notify-all">
                                {{__('View all')}}
                                <i class="fi-arrow-right"></i>
                            </a>
                        </div>
                    </li>
                    <li class="dropdown notification-list">
                        <a class="nav-item dropdown-toggle nav-user waves-effect waves-light" data-toggle="dropdown"
                           href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="{{ get_user_avatar($userData->getUserId(), [32,32]) }}" alt="user-image"
                                 class="rounded-circle">
                            <span class="pro-user-name ml-1">
                            {{ get_username($userData->getUserId()) }} <i class="ti-angle-down"></i>
                        </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow">{{__('Welcome !')}}</h6>
                            </div>
                            <!-- item-->
                            <a href="{{ dashboard_url('profile') }}" class="dropdown-item notify-item">
                                <i class="fe-user"></i>
                                <span>{{__('Profile')}}</span>
                            </a>
                        @if(is_admin() || is_partner())
                            @if(is_enable_service('home'))
                                <!-- item-->
                                    <a href="{{ dashboard_url('my-home') }}" class="dropdown-item notify-item">
                                        <i class="fe-book-open"></i>
                                        <span>{{__('My Homes')}}</span>
                                    </a>
                            @endif
                            @if(is_enable_service('experience'))
                                <!-- item-->
                                    <a href="{{ dashboard_url('my-experience') }}" class="dropdown-item notify-item">
                                        <i class="fe-book-open"></i>
                                        <span>{{__('My Experiences')}}</span>
                                    </a>
                            @endif
                            @if(is_enable_service('car'))
                                <!-- item-->
                                    <a href="{{ dashboard_url('my-car') }}" class="dropdown-item notify-item">
                                        <i class="fe-book-open"></i>
                                        <span>{{__('My Cars')}}</span>
                                    </a>
                            @endif
                        @endif
                        @if(is_admin())
                            <!-- item-->
                                <a href="{{ dashboard_url('settings') }}" class="dropdown-item notify-item">
                                    <i class="fe-settings "></i>
                                    <span>{{__('Settings')}}</span>
                                </a>
                        @endif
                        <!-- item-->
                        <?php
                        $data = [
                            'user_id' => $userData->getUserId(),
                            'redirect_url' => current_url()
                        ];
                        ?>
                        <!-- item-->
                            <a href="{{ dashboard_url('/') }}" class="dropdown-item notify-item">
                                <i class="fe-stop-circle "></i>
                                <span>{{__('Dashboard')}}</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0)" data-action="{{ auth_url('logout') }}"
                               data-params="{{ base64_encode(json_encode($data)) }}"
                               class="dropdown-item notify-item hh-link-action">
                                <i class="fe-log-out"></i>
                                <span>{{__('Logout')}}</span>
                            </a>
                        </div>
                    </li>
                @else
                    <li class="li-login">
                        <a href="javascript: void(0);" class="nav-item "
                           data-toggle="modal"
                           data-target="#hh-login-modal">{{__('Login')}}</a>
                    </li>
                    @if(get_option('enable_partner_registration', 'on') == 'on')
                        <li class="d-none d-lg-block li-become">
                            <a href="{{url('become-a-host')}}"
                               class="nav-item become-a-host">{{__('Become a Partner')}}</a>
                        </li>
                    @endif
                @endif
            </ul>
        </div>
    </header>
    <div id="content-area">

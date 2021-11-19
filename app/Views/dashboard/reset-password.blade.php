@include('dashboard.components.header')
<div class="account-pages hh-dashboard mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-pattern">
                    <div class="card-body p-4">
                        <div class="text-center w-75 m-auto">
                            <a class="logo" href="{{ dashboard_url() }}">
                                <?php
                                $logo = get_option('dashboard_logo');
                                $logo_url = get_attachment_url($logo);
                                ?>
                                <img src="{{ $logo_url }}" alt="{{get_attachment_alt($logo)}}">
                            </a>
                            <p class="text-muted mb-4 mt-3">{{__("Enter your email address and we'll send you an email with instructions to reset your password.")}}</p>
                        </div>
                        <form id="hh-reset-password-form" action="{{ url('auth/reset-password') }}" method="post"
                              data-reload-time="1500"
                              data-validation-id="form-reset-password"
                              class="form form-action">
                            @include('common.loading')
                            <div class="form-group">
                                <label for="email-address">{{__('Email address')}}</label>
                                <input class="form-control has-validation" data-validation="required|email" type="email"
                                       id="email-address" name="email" placeholder="{{__('Email')}}">
                            </div>
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary btn-block text-uppercase"
                                        type="submit"> {{__('Reset Password')}} </button>
                            </div>
                            <div class="form-message">

                            </div>
                        </form>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-white-50">
                            {{__('Back to')}} <a href="{{ url('auth/login') }}"
                                                 class="text-white ml-1"><b>{{__('Login')}}</b></a></p>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
@include('dashboard.components.footer')

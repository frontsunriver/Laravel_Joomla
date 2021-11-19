@include('dashboard.components.header')
<?php
enqueue_style('confirm-css');
enqueue_script('confirm-js');
?>
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content mt-2">
            {{--Start Content--}}
            <div class="card-box">
                <div class="header-area d-flex align-items-center">
                    <h4 class="header-title mb-0 d-block">{{__('Email Checker')}}</h4>
                </div>
                <p class="mt-2 text-muted font-italic">{{__('Send a Test email')}}</p>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <form action="{{dashboard_url('email-checker')}}" class="form form-action relative mt-2"
                              data-validation-id="form-email-checker">
                            @include('common.loading')
                            <div class="form-group">
                                <label for="email-checker-to">{{__('Send To:')}}</label>
                                <input id="email-checker-to" type="email" class="form-control has-validation"
                                       name="email_to"
                                       data-validation="required">
                            </div>
                            <div class="form-group">
                                <label for="email-checker-subject">{{__('Subject')}}</label>
                                <input id="email-checker-subject" type="text" class="form-control has-validation"
                                       data-validation="required" name="email_subject"
                                       value="{{__('Email Checker')}}">
                            </div>
                            <div class="form-group">
                                <label for="email-checker-content">{{__('Demo Content:')}}</label>
                                <input id="email-checker-content" type="text" class="form-control has-validation"
                                       name="email_content" value="{{__('This is a test mail sent from AweBooking')}}"
                                       data-validation="required">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">{{__('Send Now')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')

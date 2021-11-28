@include('dashboard.components.header')
<?php
enqueue_script('nice-select-js');
enqueue_style('nice-select-css');
?>
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content mt-2">
            {{--Start Content--}}
            @include('dashboard.components.breadcrumb', ['heading' => __('API Settings')])
            <div id="api-settings">
                <div class="card-box">
                    <div class="header-area d-flex align-items-center">
                        <h4 class="header-title mb-0">{{__('API Settings')}}</h4>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4">
                            <?php
                            $api_lifetime = get_opt('api_lifetime', 30);
                            $api_lifetime_type = get_opt('api_lifetime_type', 'day');
                            ?>
                            <form action="{{dashboard_url('save-api-settings')}}"
                                  class="form-action form-sm relative mt-3">
                                @include('common.loading')
                                <div class="form-group">
                                    <label for="api-lifetime">{{__('API Token Lifetime')}}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="api_lifetime" id="api-lifetime"
                                               value="{{ $api_lifetime }}">
                                        <div class="input-group-append">
                                            <select name="api_lifetime_type" id="api-lifetime-type"
                                                    data-plugin="customselect">
                                                <option value="seconds"
                                                        @if($api_lifetime_type == 'seconds') selected @endif>{{__('Seconds')}}</option>
                                                <option value="minute"
                                                        @if($api_lifetime_type == 'minute') selected @endif>{{__('Minute')}}</option>
                                                <option value="hour"
                                                        @if($api_lifetime_type == 'hour') selected @endif>{{__('Hour')}}</option>
                                                <option value="day"
                                                        @if($api_lifetime_type == 'day') selected @endif>{{__('Day')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success">{{__('Save Change')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')

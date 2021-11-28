@include('dashboard.components.header')
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content mt-2">
            {{--Start Content--}}
            <div class="card-box">
                <div class="header-area">
                    <h4 class="header-title">{{__('TinyPNG Compress')}}</h4>
                    <p class="description mb-0">{{__('TinyPNG uses smart lossy compression techniques to reduce the file size of your image files')}}</p>
                    <p class="description">{!! balanceTags(__('Get your API Key at <a target="_blank" href="https://tinypng.com/developers">https://tinypng.com/developers</a>')) !!}</p>
                </div>
                <div class="row">
                    <?php
                    $enable_tinypng = get_opt('tinypng_enable', 'off');
                    ?>
                    @if($enable_tinypng == 'on')
                        <div class="col-12 col-md-6">
                            <?php
                            $checked_key = false;
                            try {
                                \Tinify\setKey(get_opt('tinypng_api_key', ''));
                                \Tinify\validate();
                                $checked_key = true;
                            } catch(\Tinify\Exception $e) {
                            ?>
                            <div class="alert alert-danger">
                                {{__('Your API Key is invalid')}}
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        @if($checked_key)
                            <div class="w-100"></div>
                            <div class="col-12 col-md-6">
                                <div class="card-box card-border widget-rounded-circle card-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                                <i class="fe-bar-chart-line- font-22 avatar-title text-info"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-right">
                                                <h3 class="mt-1"><span
                                                        data-plugin="counterup">{{\Tinify\getCompressionCount()}}</span>
                                                </h3>
                                                <p class="text-muted mb-1 text-truncate">{{__('Compressed')}}</p>
                                            </div>
                                        </div>
                                    </div> <!-- end row-->
                                </div>
                            </div>
                        @endif
                    @endif
                    <div class="w-100"></div>
                    <div class="col-12 col-md-6">
                        <form action="{{dashboard_url('tinypng-compress-save')}}" class="form form-action mt-4"
                              data-validation-id="form-tinypng-compress"
                              method="post">
                            @include('common.loading')
                            <?php
                            $tinypng_enable = get_opt('tinypng_enable', 'off');
                            $tinypng_api_key = get_opt('tinypng_api_key', '');
                            ?>
                            <div id="setting-tinypng-enable" class="form-group">
                                <label for="tinypng-enable">
                                    {{__('Enable TinyPNG')}}
                                </label><br/>
                                <input type="checkbox" id="tinypng-enable" name="tinypng_enable"
                                       data-plugin="switchery" data-color="#1abc9c" value="on"
                                       @if($tinypng_enable == 'on') checked @endif/>
                            </div>
                            <div id="setting-tinypng-api-key" class="form-group" data-condition="tinypng-enable:is(on)">
                                <label for="tinypng-api-key">{{__('API Key')}}</label>
                                <input type="text" class="form-control" id="tinypng-api-key" name="tinypng_api_key"
                                       value="{{$tinypng_api_key}}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">{{__('Save')}}</button>
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

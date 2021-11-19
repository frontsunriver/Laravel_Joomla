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

    <title>{{__('System Tools')}}</title>

    <?php do_action('header'); ?>
    <link href="{{asset('css/vendor.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/main.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/frontend.min.css')}}" rel="stylesheet" type="text/css">
</head>
<body class="awe-booking authentication-bg authentication-bg-pattern">
<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body p-4">
                        <ul class="nav nav-tabs nav-bordered nav-justified">
                            <li class="nav-item active">
                                <a href="#system-tool-system" data-toggle="tab" aria-expanded="false"
                                   class="nav-link active">{{__('System Tools')}}</a>
                            </li>
                            <li class="nav-item">
                                <a href="#system-tool-import" data-toggle="tab" aria-expanded="true"
                                   class="nav-link">{{__('Import Data')}}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="system-tool-system">
                                <form action="{{url('system-tools')}}" class="form form-action relative"
                                      data-validation-id="form-system-tools">
                                    @include('common.loading')
                                    <div class="form-content mt-4">
                                        <div class="form-group">
                                            <div class="radio radio-success">
                                                <input type="radio" name="system_tool" value="clear_cache"
                                                       id="system-clear-cache" checked>
                                                <label for="system-clear-cache">{{__('Clear All Cache')}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="radio radio-success">
                                                <input type="radio" name="system_tool" value="clear_view"
                                                       id="system-clear-view">
                                                <label for="system-clear-view">{{__('Clear View Cache')}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="radio radio-success">
                                                <input type="radio" name="system_tool" value="update_version"
                                                       id="system-version">
                                                <label for="system-version">{{__('Update New Version')}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="radio radio-success">
                                                <input type="radio" name="system_tool" value="symlink"
                                                       id="system-symlink">
                                                <label for="system-symlink">{{__('Create Storage Link')}}</label>
                                            </div>
                                        </div>
                                        @if(app()->isDownForMaintenance())
                                            <div class="form-group">
                                                <div class="radio radio-success">
                                                    <input type="radio" name="system_tool" value="on_coming"
                                                           id="system-off-coming">
                                                    <label
                                                        for="system-off-coming">{{__('Turn Off Coming Soon')}}</label>
                                                </div>
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <div class="radio radio-success">
                                                    <input type="radio" name="system_tool" value="off_coming"
                                                           id="system-on-coming">
                                                    <label for="system-on-coming">{{__('Turn On Coming Soon')}}</label>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group mt-3">
                                        <input type="password" id="system-password" class="form-control has-validation"
                                               data-validation="required" name="password"
                                               placeholder="{{__('Enter System Password')}}">
                                    </div>
                                    <div class="form-group mt-4">
                                        <input type="submit" name="sm" class="btn btn-danger btn-block"
                                               value="{{__('Run')}}">
                                    </div>
                                    <div class="form-message"></div>
                                </form>
                            </div>
                            <div class="tab-pane" id="system-tool-import">
                                <?php
                                enqueue_style('confirm-css');
                                enqueue_script('confirm-js');
                                ?>
                                @if (file_exists(storage_path('imported')))
                                    <div class="row">
                                        <div class="alert alert-warning">
                                            {{__('You already have imported! However, you can still re-import.')}}
                                        </div>
                                    </div>
                                @endif
                                <div class="awe-import-progress">
                                    <small
                                        class="mb-4 d-block text-danger">{{__('Note: Your database will be reset before import demo data.')}}
                                    </small>
                                    <div id="awe-import-label" class="mt-2">
                                        <div class="title"><b>{{__('Import Progress')}}</b>
                                            <div class="import-loader"></div>
                                            <br/>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <input type="password" id="system-password-import"
                                               class="form-control has-validation" data-validation="required"
                                               name="password" placeholder="{{__('Enter System Password')}}">
                                    </div>
                                    <div class="buttons">
                                        <a href="{{ url('/') }}" class="btn btn-success btn-block" id="awe_import_demo"
                                           data-action="{{ url('import-data') }}"
                                           data-confirm="{{__('Note: Your database will be reset before import demo data. Are you sure want to install demo data?')}}">{{__('Import Data')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-white-50">{{__('Return to')}} <a href="{{ url('/') }}"
                                                                        class="text-white ml-1"><b>{{__('Home page')}}</b></a>
                        </p>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->

<footer class="footer footer-alt">
    {!! balanceTags(get_option('copy_right')) !!}
</footer>

<script src="{{asset('js/vendor.min.js')}}"></script>

<?php do_action('footer'); ?>
<?php do_action('init_footer'); ?>
<?php do_action('init_frontend_footer'); ?>
<script>
    (function ($) {
        $(document).ready(function () {
            if ($('#awe_import_demo').length) {
                $('#awe_import_demo').click(function (e) {
                    e.preventDefault();
                    var t = $(this);
                    var cof = confirm(t.data('confirm'));
                    if (cof) {
                        $('#awe-import-label').find('.item.done').remove();
                        $('#awe-import-label').fadeIn();
                        $('#awe_import_demo').css({'opacity': '0.3'});
                        $('#awe-import-label .title').find('.import-loader').css({'display': 'inline-block'});
                        importDemo(0);

                        function importDemo(step = 1) {
                            $.post(t.data('action'), {
                                'step': step,
                                '_token': $('meta[name="csrf-token"]').attr('content'),
                                'password': $('#system-password-import').val()
                            }, function (respon) {
                                if (!respon.status) {
                                    alert(respon.message);
                                    $('#awe-import-label').fadeOut();
                                    $('#awe_import_demo').css({'opacity': '1'});
                                    $('#awe-import-label .title').find('.import-loader').css({'display': 'none'});
                                } else {
                                    if (respon.next_step === 'final') {
                                        $('#awe-import-label .title').find('.import-loader').hide();
                                        $('#awe-import-label').append('<span class="item done">Completed!</span>');
                                        $('#awe_import_demo').css({'opacity': '1'});
                                    } else {
                                        $('#awe-import-label').append('<span class="item done">' + respon.label + ' <span>&#10004;</span></span>');
                                        importDemo(respon.next_step);
                                    }
                                }
                            }, 'json');
                        }
                    }
                });
            }
        });
    })(jQuery);
</script>
<script src="{{asset('js/frontend.js')}}"></script>
</body>
</html>

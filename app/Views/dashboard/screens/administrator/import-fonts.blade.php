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
                    <h4 class="header-title mb-0">{{__('Import Icon Fonts')}}</h4>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="awe-import-fonts-progress">
                            <form action="{{ dashboard_url('import-fonts') }}" method="post"
                                  class="dropzone dz-clickable text-center form-file-action" id="myAwesomeDropzone"
                                  enctype="multipart/form-data">
                                @include('common.loading')
                                <div class="dz-message needsclick mt-2 mb-2">
                                    <i class="h1 text-muted dripicons-cloud-upload icon-no"></i>
                                    <i class="fe-check-circle icon-yes"></i>
                                    <h3 data-text-origin="{{__('Drop files here or click to upload.')}}"
                                        data-text-uploaded="{{__('File #_# has been selected')}}">{{__('Drop files here or click to upload.')}}</h3>
                                    <input type="file" name="fonts" accept=".zip"/>
                                </div>
                                <div class="form-message"></div>
                                <button class="btn btn-success w-100">{{__('Upload Now')}}</button>
                            </form>
                        </div>
                        <div class="alert alert-warning mt-3">
                            <p class="mb-0"><i
                                    class="mdi mdi-alert-outline mr-2"></i>{!! __('Upload a .zip file that includes the svg fonts. <br/>Reference: <a target="_blank" href="https://www.flaticon.com/">flaticon.com</a>') !!}
                            </p>
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

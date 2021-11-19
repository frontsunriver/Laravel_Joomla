@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.final.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.final.title') }}
@endsection

@section('container')
    <div class="awe-import-progress">
       {{--<ul>
           <li id="awe-import-post" style="width: 10%;"><span class="progress"></span></li>
           <li id="awe-import-page" style="width: 10%;"><span class="progress"></span></li>
           <li id="awe-import-home" style="width: 25%;"><span class="progress"></span></li>
           <li id="awe-import-menu" style="width: 10%;"><span class="progress"></span></li>
           <li id="awe-import-media" style="width: 5%;"><span class="progress"></span></li>
           <li id="awe-import-setting" style="width: 40%;"><span class="progress"></span></li>
       </ul>--}}
        <div id="awe-import-label">
            <div class="title"><b>Import Progress</b> <div class="loader"></div><br /></div>
        </div>
    </div>
    <div class="buttons">
        <a href="{{ url('/') }}" class="button">{{ trans('installer_messages.final.exit') }}</a>
        <a href="{{ url('/') }}" class="button btn-green" id="awe_import_demo" data-action="{{ url('import-demo') }}">Import demo</a>
    </div>
@endsection

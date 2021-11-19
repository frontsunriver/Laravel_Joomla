@include('dashboard.components.header')
<?php
enqueue_style('context-menu');
enqueue_script('context-menu-pos');
enqueue_script('context-menu');

enqueue_style('confirm-css');
enqueue_script('confirm-js');
?>
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            {{--start content--}}
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="page-title">
                                {{__('Media library')}}
                                <a class="btn btn-info btn-xs waves-effect waves-light ml-1"
                                   data-toggle="collapse" href="#hh-media-add-new" aria-expanded="true">{{__('new')}}
                                </a>
                            </h4>
                            <?php
                            $sort = request()->get('sort', 'grid');
                            ?>
                            <div class="list-gid-sorts">
                                <a href="{{ add_query_arg('sort', 'list', current_url()) }}"
                                   class="list @if($sort == 'list') active @endif"><i
                                        class=" ti-layout-list-thumb "></i></a>
                                <a href="{{ add_query_arg('sort', 'grid', current_url()) }}"
                                   class="grid @if($sort == 'grid') active @endif"><i class="ti-view-grid"></i></a>
                            </div>
                        </div>

                        <?php
                        enqueue_style('dropzone-css');
                        enqueue_script('dropzone-js');
                        ?>
                        <div id="hh-media-add-new" class="hh-media-upload-area collapse mt-3">
                            <form action="{{ dashboard_url('add-media') }}" method="post" class="hh-dropzone relative"
                                  id="hh-upload-form" enctype="multipart/form-data">
                                @include('common.loading')
                                <div class="fallback">
                                    <input name="file" type="file" multiple/>
                                </div>
                                <div class="dz-message text-center needsclick">
                                    <i class="h1 text-muted dripicons-cloud-upload"></i>
                                    <h3>{{__('Drop files here or click to upload.')}}</h3>
                                    <p class="text-muted">
                                        <span>{{get_media_upload_message()['type']}}</span>
                                        <span>{{get_media_upload_message()['size']}}</span>
                                </div>
                            </form>
                        </div>
                        <div class="hh-all-media mt-3">
                            <form action="{{ dashboard_url('all-media') }}" class="form form-all-media" method="post">
                                <input type="hidden" name="sort" value="{{ $sort }}">
                            </form>
                            <div class="hh-all-media-render relative">
                                @include('common.loading')
                                @if($sort == 'grid')
                                    <ul class="render"></ul>
                                @else
                                    <?php
                                    enqueue_script('nice-select-js');
                                    enqueue_style('nice-select-css');
                                    ?>
                                    <div class="action-toolbar">
                                        <form class="hh-form form-inline"
                                              action="{{dashboard_url('media-bulk-actions')}}"
                                              data-target="#table-my-media" method="post">
                                            <select class="mr-1 min-w-100" name="action" data-plugin="customselect">
                                                <option value="none">{{__('Bulk Actions')}}</option>
                                                <option value="delete">{{__('Delete')}}</option>
                                            </select>
                                            <button type="submit" class="btn btn-success">{{__('Apply')}}</button>
                                        </form>
                                    </div>
                                    <?php
                                    enqueue_style('datatables-css');
                                    enqueue_script('datatables-js');

                                    $order = request()->get('order', 'desc');
                                    $_order = ($order == 'asc') ? 'desc' : 'asc';
                                    $url = add_query_arg([
                                        'orderby' => 'media_title',
                                        'order' => $_order
                                    ]);

                                    ?>
                                    <table id="table-my-media" class="table table-large mb-0 dt-responsive nowrap w-100"
                                           data-plugin="datatable"
                                           data-paging="false"
                                           data-export="off"
                                           data-ordering="false">
                                        <thead>
                                        <tr>
                                            <th data-priority="-1" class="hh-checkbox-td">
                                                <div class="checkbox checkbox-success hh-check-all">
                                                    <input id="hh-checkbox-all--my-media" type="checkbox">
                                                    <label for="hh-checkbox-all--my-media"><span
                                                            class="d-none">{{__('Check All')}}</span></label>
                                                </div>
                                            </th>
                                            <th data-priority="1">
                                                <a href="{{ $url }}" class="order">
                                                    <span class="exp">{{__('Name')}}</span>
                                                    @if($order == 'asc') <i class="icon-arrow-down"></i> @else <i
                                                        class="icon-arrow-up"></i> @endif
                                                </a>
                                            </th>
                                            <th data-priority="2">
                                                {{__('Size/Type')}}
                                            </th>
                                            <th data-priority="2">
                                                {{__('Author')}}
                                            </th>
                                            <th data-priority="3">
                                                {{__('Date')}}
                                            </th>
                                            <th data-priority="-1" class="text-center">{{__('Actions')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody class="render">

                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--end content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
<div id="hh-media-item-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <form class="form form-action form-update-media-item-modal relative"
                  data-validation-id="form-update-media-item"
                  action="{{ dashboard_url('update-media-item-detail') }}" method="post">
                @include('common.loading')
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Attachment Details')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect"
                            data-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit"
                            class="btn btn-info waves-effect waves-light">{{__('Update')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->
@include('dashboard.components.footer')

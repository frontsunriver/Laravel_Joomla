@include('dashboard.components.header')
<?php
enqueue_style('confirm-css');
enqueue_script('confirm-js');
?>

<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            @include('dashboard.components.breadcrumb', ['heading' => __('My Experiences')])
            {{--Start Content--}}
            <div class="card-box card-list-post">
                <div class="header-area d-flex align-items-center">
                    <h4 class="header-title mb-0">{{__('All Experiences')}}</h4>
                    <form class="form-inline right d-none d-sm-block" method="get">
                        <div class="form-group">
                            <?php
                            $search = request()->get('_s');
                            $order = request()->get('order', 'desc');
                            ?>
                            <input type="text" class="form-control" name="_s"
                                   value="{{ $search }}"
                                   placeholder="{{__('Search by id, title')}}">
                        </div>
                        <button type="submit" class="btn btn-default"><i class="ti-search"></i></button>
                    </form>
                </div>
                <?php
                enqueue_style('datatables-css');
                enqueue_script('datatables-js');
                enqueue_script('pdfmake-js');
                enqueue_script('vfs-fonts-js');
                enqueue_script('nice-select-js');
                enqueue_style('nice-select-css');
                ?>

                <div class="action-toolbar">
                    <form class="hh-form form-inline" action="{{dashboard_url('experience-bulk-actions')}}"
                          data-target="#table-my-experience" method="post">
                        <select class="mr-1 min-w-100" name="action" data-plugin="customselect">
                            <option value="none">{{__('Bulk Actions')}}</option>
                            <option value="publish">{{__('Publish')}}</option>
                            <option value="pending">{{__('Pending')}}</option>
                            <option value="draft">{{__('Draft')}}</option>
                            <option value="trash">{{__('Trash')}}</option>
                            <option value="delete">{{__('Delete')}}</option>
                        </select>
                        <button type="submit" class="btn btn-success">{{__('Apply')}}</button>
                    </form>
                </div>
                <?php
                $tableColumns = [1, 2, 3, 4, 5];
                ?>

                <table id="table-my-experience" class="table table-large mb-0 dt-responsive nowrap w-100"
                       data-plugin="datatable"
                       data-paging="false"
                       data-export="on"
                       data-csv-name="{{__('Export to CSV')}}"
                       data-pdf-name="{{__('Export to PDF')}}"
                       data-cols="{{ base64_encode(json_encode($tableColumns)) }}"
                       data-ordering="false">
                    <thead>
                    <tr>
                        <?php
                        $_order = ($order == 'asc') ? 'desc' : 'asc';
                        $url = add_query_arg([
                            'orderby' => 'post_title',
                            'order' => $_order
                        ]);
                        ?>
                        <th data-priority="-1" class="hh-checkbox-td">
                            <div class="checkbox checkbox-success hh-check-all d-none d-md-block">
                                <input id="hh-checkbox-all--my-experience" type="checkbox">
                                <label for="hh-checkbox-all--my-experience"><span
                                        class="d-none">{{__('Check All')}}</span></label>
                            </div>
                        </th>
                        <th data-priority="1" class="force-show">
                            <a href="{{ $url }}" class="order">
                                <span class="exp">{{__('Name')}}</span>
                                @if($order == 'asc') <i class="icon-arrow-down"></i> @else <i
                                    class="icon-arrow-up"></i> @endif
                            </a>
                        </th>
                        <?php
                        $_order = ($order == 'asc') ? 'desc' : 'asc';
                        $url = add_query_arg([
                            'orderby' => 'base_price',
                            'order' => $_order
                        ]);
                        ?>
                        <th data-priority="3">
                            <a href="{{ $url }}" class="order">
                                <span class="exp">{{__('Price')}}</span>
                                @if ($order == 'asc') <i class="icon-arrow-down"></i> @else <i
                                    class="icon-arrow-up"></i> @endif
                            </a>
                        </th>
                        <th data-priority="4" class="text-center">
                            <div class="dropdown">
                                <a class="dropdown-toggle not-show-caret" id="dropdownFilterStatus"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="exp">{{__('Status')}}</span>
                                    <i class="icon-arrow-down"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownFilterStatus">
                                    <a class="dropdown-item"
                                       href="{{ remove_query_arg('status') }}">{{__('All')}}</a>
                                    <?php
                                    $status = service_status_info();
                                    foreach ($status as $key => $_status) {
                                    $url = add_query_arg('status', $key);
                                    ?>
                                    <a class="dropdown-item"
                                       href="{{ $url }}">{{ __($_status['name']) }}</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </th>
                        <th data-priority="5" class="text-center"><span class="exp">{{__('Max. People')}}</span></th>
                        <th data-priority="6"><span class="exp">{{__('Type')}}</span></th>
                        <th data-priority="-1" class="text-center">{{__('Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($allExperiences['total'])
                        @foreach ($allExperiences['results'] as $item)
                            <?php
                            $experienceID = $item->post_id;
                            $thumbnail_id = get_experience_thumbnail_id($experienceID);
                            $thumbnail = get_attachment_url($thumbnail_id, [75, 75]);
                            $experienceType = $item->experience_type;
                            $term = get_term_by('term_id', $experienceType);
                            ?>
                            <tr>
                                <td class="align-middle hh-checkbox-td">
                                    <div class="checkbox checkbox-success d-none d-md-block">
                                        <input type="checkbox" name="post_id" value="{{$item->post_id}}"
                                               id="hh-check-all-item-{{$item->post_id}}" class="hh-check-all-item">
                                        <label for="hh-check-all-item-{{$item->post_id}}"></label>
                                    </div>
                                </td>
                                <td class="align-middle force-show">
                                    <div class="media align-items-center">
                                        <img src="{{ $thumbnail }}" class="d-none d-md-block mr-3"
                                             alt="{{ get_attachment_alt($thumbnail_id) }}">
                                        <div class="media-body">
                                            <h5 class="title-overflow m-0">
                                                <a class="text"
                                                   href="{{ get_experience_permalink($experienceID, $item->post_slug) }}"
                                                   target="_blank">{{ get_translate($item->post_title) }}</a>
                                                <span
                                                    class="text-muted d-none d-md-inline-block"> - {{ $experienceID }}</span>
                                            </h5>
                                            <span
                                                class="exp d-none">[{{ $experienceID }}] {{ get_translate($item->post_title) }}</span>
                                            <div class="quick-action-links d-none d-md-block">
                                                <a class="quick-link-item" target="_blank"
                                                   href="{{ dashboard_url('edit-experience', $experienceID) }}">{{__('Edit')}}</a>
                                                @if($item->status != 'trash')
                                                    <?php
                                                    $data = [
                                                        'serviceID' => $experienceID,
                                                        'serviceEncrypt' => hh_encrypt($experienceID),
                                                        'serviceType' => 'experience',
                                                        'status' => 'trash'
                                                    ];
                                                    ?>
                                                    <a class="quick-link-item quick-link-item-trash hh-link-action hh-link-change-status-experience"
                                                       data-action="{{ dashboard_url('change-status-experience') }}"
                                                       data-parent="tr"
                                                       data-is-delete="false"
                                                       data-params="{{ base64_encode(json_encode($data)) }}"
                                                       href="javascript: void(0)">{{ __('Trash') }}</a>
                                                    @if($item->status == 'publish')
                                                        <a class="quick-link-item"
                                                           href="{{ get_experience_permalink($experienceID, $item->post_slug) }}"
                                                           target="_blank">{{ __('View') }}</a>

                                                        <a class="quick-link-item" href="javascript:void(0)"
                                                           data-params="{{ base64_encode(json_encode($data)) }}"
                                                           data-toggle="modal" data-target="#hh-get-qrcode-modal">
                                                            {{ __('QR Code') }}
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="exp">{{ convert_price($item->base_price) }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="service-status {{ $item->status }} status-icon"
                                         data-toggle="tooltip" data-placement="right" title=""
                                         data-original-title="{{ service_status_info($item->status)['name'] }}"><span
                                            class="exp d-none">{{ service_status_info($item->status)['name'] }}</span>
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="exp">{{ $item->number_of_guest }}</span>
                                </td>
                                <td class="align-middle">
                                    <span class="exp">@if($term) {{ get_translate($term->term_title) }} @endif</span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="dropdown dropleft">
                                        <?php
                                        $data = [
                                            'serviceID' => $experienceID,
                                            'serviceEncrypt' => hh_encrypt($experienceID),
                                            'serviceType' => 'experience'
                                        ];
                                        ?>
                                        <a href="javascript: void(0)" class="dropdown-toggle table-action-link"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                class="ti-settings"></i></a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" target="_blank"
                                               href="{{ dashboard_url('edit-experience', $experienceID) }}">{{__('Edit')}}</a>
                                            <?php
                                            $service_status_info = service_status_info();
                                            ?>
                                            @foreach($service_status_info as $key => $status)
                                                <?php $data['status'] = $key; ?>
                                                <a class="dropdown-item hh-link-action hh-link-change-status-experience"
                                                   data-action="{{ dashboard_url('change-status-experience') }}"
                                                   data-parent="tr"
                                                   data-is-delete="false"
                                                   data-params="{{ base64_encode(json_encode($data)) }}"
                                                   href="javascript: void(0)">{{ __($status['name']) }}</a>
                                            @endforeach
                                            <a class="dropdown-item hh-link-action hh-link-duplicate-experience"
                                               data-action="{{ dashboard_url('duplicate-experience') }}"
                                               data-parent="tr"
                                               data-is-delete="false"
                                               data-params="{{ base64_encode(json_encode($data)) }}"
                                               href="javascript: void(0)">{{ __('Duplicate') }}</a>

                                            <a class="dropdown-item hh-link-action hh-link-delete-experience text-danger    "
                                               data-action="{{ dashboard_url('delete-experience-item') }}"
                                               data-parent="tr"
                                               data-is-delete="true"
                                               data-confirm="yes"
                                               data-confirm-title="{{__('System Alert')}}"
                                               data-confirm-question="{{__('Are you sure want to delete this experience?')}}"
                                               data-confirm-button="{{__('Delete it!')}}"
                                               data-params="{{ base64_encode(json_encode($data)) }}"
                                               href="javascript: void(0)">{{__('Delete')}}
                                            </a>
                                            @if($item->status == 'publish')
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                   data-params="{{ base64_encode(json_encode($data)) }}"
                                                   data-toggle="modal" data-target="#hh-get-qrcode-modal">
                                                    {{ __('QR Code') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="d-none"></td>
                            <td class="d-none"></td>
                            <td class="d-none"></td>
                            <td class="d-none"></td>
                            <td class="d-none"></td>
                            <td class="d-none"></td>
                            <td colspan="7">
                                <h4 class="mt-3 text-center">{{__('No experience yet.')}}</h4>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="clearfix mt-2">
                    {{ dashboard_pagination(['total' => $allExperiences['total']]) }}
                </div>
            </div>

            @include('dashboard.components.qr-modal')
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')

@include('dashboard.components.header')
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            @include('dashboard.components.breadcrumb', ['heading' => __('Payout')])
            {{--Start Content--}}
            <?php
            $search = request()->get('_s');
            $order = request()->get('order', 'asc');
            ?>
            <div class="card-box">
                <div class="header-area d-flex align-items-center">
                    <h4 class="header-title mb-0">{{__('All Payouts')}}</h4>
                    <form class="form-inline right d-none d-sm-block" method="get">
                        <div class="form-group">
                            <input type="text" class="form-control" name="_s"
                                   value="{{ $search }}"
                                   placeholder="{{__('Search by id')}}">
                        </div>
                        <button type="submit" class="btn btn-default"><i class="ti-search"></i></button>
                    </form>
                </div>
                <?php
                enqueue_style('datatables-css');
                enqueue_script('datatables-js');
                enqueue_script('pdfmake-js');
                enqueue_script('vfs-fonts-js');
                ?>
                <?php
                $tableColumns = [0, 1, 2, 3, 4];
                ?>
                <table class="table table-large mb-0 dt-responsive nowrap w-100" data-plugin="datatable"
                       data-paging="false"
                       data-export="on"
                       data-csv-name="{{__('Export to CSV')}}"
                       data-pdf-name="{{__('Export to PDF')}}"
                       data-cols="{{ base64_encode(json_encode($tableColumns)) }}"
                       data-ordering="false">
                    <thead>
                    <tr>
                        <th data-priority="1">{{__('Payout ID')}}</th>
                        <th data-priority="2">
                            {{__('Name')}}
                        </th>
                        <th data-priority="1" class="text-center">
                            {{__('Amount')}}
                        </th>
                        <th data-priority="2" class="text-center">
                            <div class="dropdown">
                                <a class="dropdown-toggle not-show-caret" id="dropdownFilterStatus"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{__('Status')}}
                                    <i class="icon-arrow-down"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownFilterStatus">
                                    <a class="dropdown-item" href="{{ remove_query_arg('status') }}">{{__('All')}}</a>
                                    <a class="dropdown-item"
                                       href="{{ add_query_arg('status', 'pending') }}">{{__('Pending')}}</a>
                                    <a class="dropdown-item"
                                       href="{{ add_query_arg('status', 'completed') }}">{{__('Completed')}}</a>
                                </div>
                                <span class="exp d-none">{{__('Status')}}</span>
                            </div>
                        </th>
                        <th data-priority="6">
                            {{__('Created At')}}
                        </th>
                        <th data-priority="-1" class="text-center">{{__('Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($allPayouts['total'])
                        @foreach ($allPayouts['results'] as $item)
                            <tr>
                                <td class="align-middle">
                                    {{ $item->payout_id }}
                                </td>
                                <td class="align-middle">
                                    <p class="mb-0"><i class="text-muted exp">{{ get_username($item->user_id) }}</i>
                                    </p>
                                </td>
                                <td class="align-middle text-center">
                                    {{convert_price($item->amount)}}
                                </td>
                                <td class="align-middle text-center">
                                    <?php
                                    $payout_status = payout_status_info($item->status);
                                    ?>
                                    <span class="booking-status booking-bgr {{$item->status}}"><span
                                            class="exp">{{$payout_status['name']}}</span></span>
                                </td>
                                <td class="align-middle">
                                    {{date(hh_date_format(), $item->created)}}
                                </td>
                                <td class="align-middle text-center">
                                    <div class="dropdown d-inline-block">
                                        <a href="javascript: void(0)" class="dropdown-toggle table-action-link"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                class="ti-settings"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <?php
                                            $data = [
                                                'payoutID' => $item->ID,
                                                'payoutEncrypt' => hh_encrypt($item->ID),
                                            ];
                                            ?>
                                            <a class="dropdown-item"
                                               data-toggle="modal"
                                               data-target="#modal-show-payout-detail"
                                               data-params="{{ base64_encode(json_encode($data)) }}"
                                               href="javascript: void(0)">{{__('View')}}</a>
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
                            <td colspan="6">
                                <h4 class="mt-3 text-center">{{__('No payout yet.')}}</h4>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="clearfix">
                    {{ dashboard_pagination(['total' => $allPayouts['total']]) }}
                </div>
            </div>
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
<div class="modal fade hh-get-modal-content" id="modal-show-payout-detail" tabindex="-1" role="dialog"
     aria-hidden="true" data-url="{{ dashboard_url('get-payout-detail') }}">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('common.loading')
            <div class="modal-header">
                <h4 class="modal-title">{{__('Payout Detail')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">{{__('Close')}}</button>
            </div>
        </div>
    </div>
</div>
@include('dashboard.components.footer')

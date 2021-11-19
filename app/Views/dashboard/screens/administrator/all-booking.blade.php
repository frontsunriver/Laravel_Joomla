@include('dashboard.components.header')
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            @include('dashboard.components.breadcrumb', ['heading' => __('All Reservations')])
            {{--Start Content--}}
            <div class="card-box">
                <div class="header-area d-flex align-items-center">
                    <h4 class="header-title mb-0">{{__('All Reservations')}}</h4>
                    <form class="form-inline right d-none d-sm-block" method="get">
                        <div class="form-group">
                            <?php
                            $search = request()->get('_s');
                            $order = request()->get('order', 'desc');
                            ?>
                            <input type="text" class="form-control" name="_s"
                                   value="{{ $search }}"
                                   placeholder="{{__('Search by id, boking id, email')}}">
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
                $tableColumns = [0, 1, 2, 3, 4, 5];
                ?>
                <table class="table  table-large mb-0 dt-responsive nowrap w-100" data-plugin="datatable"
                       data-paging="false"
                       data-export="on"
                       data-csv-name="{{__('Export to CSV')}}"
                       data-pdf-name="{{__('Export to PDF')}}"
                       data-cols="{{ base64_encode(json_encode($tableColumns)) }}"
                       data-ordering="false">
                    <thead>
                    <tr>
                        <th data-priority="1">
                            <?php
                            $_order = ($order == 'asc') ? 'desc' : 'asc';
                            $url = add_query_arg([
                                'orderby' => 'ID',
                                'order' => $_order
                            ]);
                            ?>
                            <a href="{{ $url }}" class="order">
                                ID
                                @if ($order == 'asc')
                                    <i class="icon-arrow-down"></i>
                                @else
                                    <i class="icon-arrow-up"></i>
                                @endif
                                <span class="exp d-none">{{__('ID')}}</span>
                            </a>
                        </th>
                        <th data-priority="2">
                            {{__('Description')}}
                        </th>
                        <th data-priority="5" class="text-center">
                            <div class="dropdown">
                                <a class="dropdown-toggle not-show-caret" type="button" id="dropdownFilterStatus"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{__('Status')}}
                                    <i class="icon-arrow-down"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownFilterStatus">
                                    <a class="dropdown-item"
                                       href="{{ remove_query_arg('status') }}">{{__('All')}}</a>
                                    <?php
                                    $allStatus = booking_status_info();
                                    foreach ($allStatus as $key => $status) {
                                    $url = add_query_arg('status', $key);
                                    ?>
                                    <a class="dropdown-item"
                                       href="{{ $url }}">{{ __($status['label']) }}</a>
                                    <?php } ?>
                                </div>
                                <span class="exp d-none">{{__('Status')}}</span>
                            </div>
                        </th>
                        <th data-priority="4" class="text-center">
                            <?php
                            $_order = ($order == 'asc') ? 'desc' : 'asc';
                            $url = add_query_arg([
                                'orderby' => 'total',
                                'order' => $_order
                            ]);
                            ?>
                            <a href="{{ $url }}" class="order ">
                                {{__('Amount')}}
                                @if ($order == 'asc')
                                    <i class="icon-arrow-down"></i>
                                @else
                                    <i class="icon-arrow-up"></i>
                                @endif
                                <span class="exp d-none">{{__('Amount')}}</span>
                            </a>
                        </th>
                        <th data-priority="6">{{__('Check In/Out')}}</th>
                        <th data-priority="6">{{__('Created Date')}}</th>
                        <th data-priority="-1" class="text-center">{{__('Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($allBooking['total'])
                        @foreach ($allBooking['results'] as $item)
                            <?php
                            $ID = $item->ID;
                            $bookingID = $item->booking_id;
                            $bookingStatus = booking_status_info($item->status);
                            $serviceType = $item->service_type;
                            $serviceObject = get_booking_data($ID, 'serviceObject');
                            ?>
                            <tr>
                                <td class="align-middle"><span class="exp">{{ $ID }}</span></td>
                                <td class="align-middle"><span
                                        class="exp">{{ get_translate($item->booking_description) }}</span></td>
                                <td class="align-middle text-center">
                                    <div class="booking-status {{ $item->status }}"><span
                                            class="exp">{{ __($bookingStatus['label']) }}</span></div>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="exp">{{ convert_price($item->total) }}</span>
                                </td>
                                <td class="align-middle">
                                    <?php
                                    $checkIn = $item->start_time;
                                    $checkOut = $item->end_time;
                                    ?>
                                    @if($serviceType == 'car')
                                        <span
                                            class="exp">{!! balanceTags(date(hh_date_format(true), $checkIn)) . '<br /><span class="d-none"> - </span><i class="fe-arrow-right"></i><br />' . balanceTags(date(hh_date_format(true), $checkOut)) !!}</span>
                                    @elseif($serviceType == 'home')
                                        @if(isset($serviceObject->booking_type) && $serviceObject->booking_type == 'per_hour')
                                            <span class="exp">{!! balanceTags(date(hh_date_format(), $checkIn)) . '<span class="d-none"> - </span><br/>'
                                           .balanceTags(date(hh_time_format(), $checkIn)).'<span class="d-none"> - </span><i class="fe-arrow-right ml-2 mr-2"></i>' . balanceTags(date(hh_time_format(), $checkOut)) !!}</span>
                                        @else
                                            <span
                                                class="exp">{!! balanceTags(date(hh_date_format(), $checkIn)) . '<span class="d-none"> - </span><i class="fe-arrow-right ml-2 mr-2"></i>' . balanceTags(date(hh_date_format(), $checkOut)) !!}</span>
                                        @endif
                                    @else
                                        <span
                                            class="exp">{!! balanceTags(date(hh_date_format(), $checkIn)) . '<span class="d-none"> - </span><i class="fe-arrow-right ml-2 mr-2"></i>' . balanceTags(date(hh_date_format(), $checkOut)) !!}</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <span
                                        class="exp">{{ balanceTags(date(hh_date_format(), $item->created_date)) }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="dropdown dropleft">
                                        <a href="javascript: void(0)" class="dropdown-toggle table-action-link"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                class="ti-settings"></i></a>
                                        <div class="dropdown-menu">
                                            <?php
                                            $data = [
                                                'bookingID' => $ID,
                                                'bookingEncrypt' => hh_encrypt($ID),
                                            ];
                                            ?>
                                            <a class="dropdown-item"
                                               data-toggle="modal"
                                               data-target="#modal-show-booking-invoice"
                                               data-params="{{ base64_encode(json_encode($data)) }}"
                                               href="javascript: void(0)">{{__('Invoice')}}</a>
                                            <?php
                                            $allStatus = booking_status_info();
                                            foreach ($allStatus as $key => $status) {
                                            $data = [
                                                'bookingID' => $ID,
                                                'bookingEncrypt' => hh_encrypt($ID),
                                                'status' => $key
                                            ];
                                            ?>
                                            <a class="dropdown-item hh-link-action ots-link-change-status-booking"
                                               data-action="{{ dashboard_url('change-booking-status') }}"
                                               data-parent="tr"
                                               data-params="{{ base64_encode(json_encode($data)) }}"
                                               href="javascript: void(0)">{{ __($status['label']) }}</a>
                                            <?php } ?>
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
                                <h4 class="mt-3 text-center">{{__('No bookings yet.')}}</h4>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="clearfix mt-2">
                    {{ dashboard_pagination(['total' => $allBooking['total']]) }}
                </div>
            </div>
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>

<div class="modal fade hh-get-modal-content" id="modal-show-booking-invoice" tabindex="-1" role="dialog"
     aria-hidden="true" data-url="{{ dashboard_url('get-booking-invoice') }}">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('common.loading')
            <div class="modal-header">
                <h4 class="modal-title">{{__('Booking Detail')}}</h4>
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

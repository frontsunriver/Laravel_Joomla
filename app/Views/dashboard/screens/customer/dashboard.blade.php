@include('dashboard.components.header')
<?php
$user = get_current_user_data();
?>
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            {{--Start Content--}}
            <div class="page-title-box">
                <h4 class="page-title">{{ __('Welcome :username', ['username' => get_username($user->getUserId())]) }}
                    !</h4>
            </div>
            <div class="card card-box">
                <h4 class="header-title mb-3">{{__('Your newest booking')}}</h4>
                <div class="hh-slimscroll" data-max-height="350px" data-min-height="150px">
                    <?php
                    enqueue_style('datatables-css');
                    enqueue_script('datatables-js');
                    enqueue_script('pdfmake-js');
                    enqueue_script('vfs-fonts-js');

                    ?>

                    <?php
                    $data = [
                        'user_type' => 'buyer',
                        'user_id' => $user->getUserId(),
                        'number' => 10,
                        'orderby' => 'ID',
                        'order' => 'desc',
                        'services' => get_enabled_service_keys()
                    ];
                    $allBooking = \App\Controllers\BookingController::get_inst()->allBookings($data);
                    ?>
                    <table
                        class="table table-large mb-0 dt-responsive nowrap w-100 @if($allBooking['total'] <= 0) no-data @endif"
                        data-plugin="datatable"
                        data-pdf="off" data-pdf-name="{{__('Export to PDF')}}"
                        data-paging="false"
                        data-ordering="false">
                        <thead>
                        <tr>
                            <th data-priority="1">
                                {{__('Booking ID')}}
                            </th>
                            <th data-priority="5" class="text-center">
                                {{__('Status')}}
                            </th>
                            <th data-priority="4" class="text-center">
                                {{__('Amount')}}
                            </th>
                            <th data-priority="6">{{__('Check In/Out')}}</th>
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
                                ?>
                                <tr>
                                    <td>{{ $item->booking_id }}</td>
                                    <td class="align-middle text-center">
                                        <div
                                            class="booking-status {{ $item->status }}">{{ __($bookingStatus['label']) }}</div>
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ convert_price($item->total) }}
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
                                    <td class="align-middle text-center">
                                        <?php
                                        $data = [
                                            'bookingID' => $ID,
                                            'bookingEncrypt' => hh_encrypt($ID),
                                        ];
                                        ?>
                                        <a href="javascript: void(0);"
                                           data-toggle="modal"
                                           data-target="#modal-show-booking-invoice"
                                           data-params="{{ base64_encode(json_encode($data)) }}"
                                           class="btn btn-xs btn-secondary"><i
                                                class=" mdi mdi-information-variant "></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">
                                    <h4 class="mt-3 text-center">{{__('No bookings yet.')}}</h4>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div> <!-- end card-box-->
            <div class="card card-box">
                <h4 class="header-title mb-3">{{__('Your Notifications')}}</h4>
                <div class="hh-slimscroll" data-max-height="350px" data-min-height="150px">
                    <?php
                    enqueue_style('datatables-css');
                    enqueue_script('datatables-js');
                    enqueue_script('pdfmake-js');
                    enqueue_script('vfs-fonts-js');
                    ?>

                    <?php
                    $allNotifications = \App\Controllers\NotificationController::get_inst()->allNotifications([]);
                    ?>
                    <table
                        class="table table-large mb-0 dt-responsive nowrap w-100 @if($allNotifications['total'] <= 0) no-data @endif"
                        data-plugin="datatable"
                        data-pdf="off" data-pdf-name="{{__('Export to PDF')}}"
                        data-paging="false"
                        data-ordering="false">
                        <thead>
                        <tr>
                            <th data-priority="1">
                                {{__('#ID')}}
                            </th>
                            <th data-priority="5" class="text-center">
                                {{__('Title')}}
                            </th>
                            <th data-priority="4" class="text-center">
                                {{__('Message')}}
                            </th>
                            <th data-priority="6">{{__('Type')}}</th>
                            <th data-priority="-1" class="text-center">{{__('Created At')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if ($allNotifications['total'])
                            @foreach ($allNotifications['results'] as $item)
                                <tr>
                                    <td>#{{ $item->ID }}</td>
                                    <td class="align-middle">
                                        {!! balanceTags($item->title) !!}
                                    </td>
                                    <td class="align-middle">
                                        {!! get_translate(balanceTags($item->message)) !!}
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="notify-item">
                                            <span class="small-info notify-{{ $item->type }}">{{ $item->type }}</span>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ date(hh_date_format(), $item->created_at) }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    <h4 class="mt-3 text-center">{{__('No messages yet.')}}</h4>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div> <!-- end card-box-->
        </div>
        {{--End content--}}
        @include('dashboard.components.footer-content')
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

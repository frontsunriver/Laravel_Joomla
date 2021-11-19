@include('dashboard.components.header')
<?php
enqueue_script('nice-select-js');
enqueue_style('nice-select-css');

enqueue_script('flatpickr-js');
enqueue_style('flatpickr-css');
?>
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            @include('dashboard.components.breadcrumb', ['heading' => __('Coupon')])
            {{--Start Content--}}
            <?php
            $search = request()->get('_s');
            $order = request()->get('order', 'asc');
            ?>
            <div class="card-box">
                <div class="header-area d-flex align-items-center">
                    <h4 class="header-title mb-0">{{__('All Coupons')}}</h4>
                    <form class="form-inline right d-none d-sm-block" method="get">
                        <div class="form-group">
                            <input type="text" class="form-control" name="_s"
                                   value="{{ $search }}"
                                   placeholder="{{__('Search by id, code')}}">
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
                <table class="table table-large mb-0 dt-responsive nowrap w-100" data-plugin="datatable"
                       data-paging="false"
                       data-ordering="false">
                    <thead>
                    <tr>
                        <?php
                        $_order = ($order == 'asc') ? 'desc' : 'asc';
                        $url = add_query_arg([
                            'orderby' => 'coupon_code',
                            'order' => $_order
                        ]);
                        ?>
                        <th data-priority="1">
                            <a href="{{ $url }}" class="order">
                                {{__('Name')}}
                                @if($order == 'asc') <i class="icon-arrow-down"></i> @else <i
                                    class="icon-arrow-up"></i> @endif
                            </a>
                        </th>
                        <th data-priority="3">
                            {{__('Description')}}
                        </th>
                        <th data-priority="4" class="text-center">
                            {{__('Price')}}
                        </th>
                        <th data-priority="5" class="text-center">
                            <div class="dropdown">
                                <a class="dropdown-toggle not-show-caret" id="dropdownFilterStatus"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{__('Status')}}
                                    <i class="icon-arrow-down"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownFilterStatus">
                                    <a class="dropdown-item" href="{{ remove_query_arg('status') }}">{{__('All')}}</a>
                                    <a class="dropdown-item" href="{{ add_query_arg('status', 'on') }}">{{__('ON')}}</a>
                                    <a class="dropdown-item"
                                       href="{{ add_query_arg('status', 'off') }}">{{__('OFF')}}</a>
                                </div>
                            </div>
                        </th>
                        <th data-priority="6">
                            {{__('Applied Date')}}
                        </th>
                        <th data-priority="-1" class="text-center">{{__('Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($allCoupons['total'])
                        @foreach ($allCoupons['results'] as $item)
                            <tr>
                                <td class="align-middle">
                                    {{ $item->coupon_code }}
                                </td>
                                <td class="align-middle">
                                    <p class="mb-0"><i
                                            class="text-muted">{{ get_translate($item->coupon_description) }}</i>
                                    </p>
                                </td>
                                <td class="align-middle text-center">
                                    @if ($item->price_type == 'fixed')
                                        {{ convert_price($item->coupon_price) }}
                                    @elseif ($item->price_type == 'percent')
                                        {{ round($item->coupon_price, 2) }}%
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <?php
                                    $data = [
                                        'couponID' => $item->coupon_id,
                                        'couponEncrypt' => hh_encrypt($item->coupon_id),
                                    ];
                                    ?>
                                    <?php
                                    enqueue_script('switchery-js');
                                    enqueue_style('switchery-css');
                                    ?>
                                    <input type="checkbox" id="coupon_status" name="coupon_status" data-parent="tr"
                                           data-plugin="switchery" data-color="#1abc9c" class="hh-checkbox-action"
                                           data-action="{{ dashboard_url('change-coupon-status') }}"
                                           data-params="{{ base64_encode(json_encode($data)) }}"
                                           value="on" @if( $item->status == 'on') checked @endif/>
                                </td>
                                <td class="align-middle">
                                    <strong>{{ date(hh_date_format(), $item->start_time) }}</strong>
                                    <i class="fe-arrow-right"></i>
                                    <strong>{{ date(hh_date_format(), $item->end_time) }}</strong>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="dropdown d-inline-block">
                                        <a href="javascript: void(0)" class="dropdown-toggle table-action-link"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                class="ti-settings"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <?php
                                            $data = [
                                                'couponID' => $item->coupon_id,
                                                'couponEncrypt' => hh_encrypt($item->coupon_id),
                                            ];
                                            ?>
                                            <a href="javascript:void(0)" class="dropdown-item" data-toggle="modal"
                                               data-params="{{ base64_encode(json_encode($data)) }}"
                                               data-target="#hh-update-coupon-modal">{{__('Edit')}}</a>
                                            <a class="dropdown-item hh-link-action hh-link-delete-coupon"
                                               data-action="{{ dashboard_url('delete-coupon-item') }}"
                                               data-parent="tr"
                                               data-is-delete="true"
                                               data-params="{{ base64_encode(json_encode($data)) }}"
                                               href="javascript: void(0)">{{__('Delete')}}</a>
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
                                <h4 class="mt-3 text-center">{{__('No coupons yet.')}}</h4>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="clearfix">
                    {{ dashboard_pagination(['total' => $allCoupons['total']]) }}
                </div>
            </div>
            <div id="hh-update-coupon-modal" class="modal fade hh-get-modal-content" tabindex="-1" role="dialog"
                 aria-hidden="true"
                 data-url="{{ dashboard_url('get-coupon-item') }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form form-action form-update-coupon-modal relative form-translation"
                              data-validation-id="form-update-coupon"
                              action="{{ dashboard_url('update-coupon-item') }}">
                            @include('common.loading')
                            <div class="modal-header">
                                <h4 class="modal-title">{{__('Update Coupon')}}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                                </button>
                            </div>
                            <div class="modal-body">
                            </div>
                            <div class="modal-footer">
                                <button type="submit"
                                        class="btn btn-info waves-effect waves-light">{{__('Update')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal -->
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')

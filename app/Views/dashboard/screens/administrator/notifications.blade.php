@include('dashboard.components.header')
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content">
            @include('dashboard.components.breadcrumb', ['heading' => __('Notifications')])
            {{--Start Content--}}
            <div class="card-box">
                <div class="header-area d-flex align-items-center">
                    <h4 class="header-title mb-0">{{__('Notifications')}}</h4>
                </div>
                <?php
                enqueue_style('datatables-css');
                enqueue_script('datatables-js');
                enqueue_script('pdfmake-js');
                enqueue_script('vfs-fonts-js');
                ?>
                <?php
                $tableColumns = [
                    __('Name'),
                    __('Price'),
                    __('Status'),
                    __('No. Guest'),
                    __('Home Type')
                ];
                ?>
                <table class="table table-large mb-0 dt-responsive nowrap w-100" data-plugin="datatable"
                       data-paging="false"
                       data-pdf="off"
                       data-pdf-name="{{__('Export to PDF')}}"
                       data-cols="{{ base64_encode(json_encode($tableColumns)) }}"
                       data-ordering="false">
                    <thead>
                    <tr>
                        <th data-priority="1">
                            {{__('#ID')}}
                        </th>
                        <th data-priority="1">
                            {{__('Title')}}
                        </th>
                        <th data-priority="3">
                            {{__('Message')}}
                        </th>
                        <th data-priority="4" class="text-center">
                            {{__('Type')}}
                        </th>
                        <th data-priority="5" class="text-center">{{__('Created at')}}</th>
                        <th data-priority="-1" class="text-center">{{__('Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($allNotifications['total'])
                        @foreach ($allNotifications['results'] as $item)
                            <tr>
                                <td class="align-middle">
                                    #{{ $item->ID }}
                                </td>
                                <td class="align-middle">
                                    {!! balanceTags(get_translate($item->title)) !!}
                                </td>
                                <td class="align-middle">
                                    {!! balanceTags(get_translate($item->message)) !!}
                                </td>
                                <td class="align-middle text-center">
                                    <div class="notify-item">
                                        <span class="small-info notify-{{ $item->type }}">{{ $item->type }}</span>
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    {{ date(hh_date_format(), $item->created_at) }}
                                </td>
                                <td class="align-middle text-center">
                                    <?php
                                    $data = [
                                        'notiID' => $item->ID,
                                        'notiEncrypt' => hh_encrypt($item->ID),
                                    ];
                                    ?>
                                    <a class="hh-link-action hh-link-delete-notification"
                                       data-action="{{ dashboard_url('delete-notification') }}"
                                       data-parent="tr"
                                       data-is-delete="true"
                                       data-params="{{ base64_encode(json_encode($data)) }}"
                                       href="javascript: void(0)">
                                        <i class="text-danger mdi mdi-delete font-16"></i>
                                    </a>
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
                                <h4 class="mt-3 text-center">{{__('No notification yet.')}}</h4>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="clearfix mt-2">
                    {{ dashboard_pagination(['total' => $allNotifications['total']]) }}
                </div>
            </div>
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')

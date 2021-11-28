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
            @include('dashboard.components.breadcrumb', ['heading' => __('Posts')])
            {{--Start Content--}}
            <?php

            $search = request()->get('_s');
            $order = request()->get('order', 'asc');
            ?>
            <div class="card-box card-list-post">
                <div class="header-area d-flex align-items-center">
                    <h4 class="header-title mb-0">{{__('All Posts')}}</h4>
                    <form class="form-inline right d-none d-sm-block" method="get">
                        <div class="form-group">
                            <input type="text" class="form-control" name="_s"
                                   value="{{ $search }}"
                                   placeholder="{{__('Search Posts')}}">
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
                    <form class="hh-form form-inline" action="{{dashboard_url('post-bulk-actions')}}"
                          data-target="#table-my-post" method="post">
                        <select class="mr-1 min-w-100" name="action" data-plugin="customselect">
                            <option value="none">{{__('Bulk Actions')}}</option>
                            <option value="publish">{{__('Publish')}}</option>
                            <option value="draft">{{__('Draft')}}</option>
                            <option value="trash">{{__('Trash')}}</option>
                            <option value="delete">{{__('Delete')}}</option>
                        </select>
                        <button type="submit" class="btn btn-success">{{__('Apply')}}</button>
                    </form>
                </div>

                <table id="table-my-post" class="table table-large mb-0 dt-responsive nowrap w-100 hh-list-service"
                       data-plugin="datatable"
                       data-paging="false"
                       data-ordering="false">
                    <thead>
                    <tr>
                        <?php
                        $_order = ($order == 'asc') ? 'desc' : 'asc';
                        $url = add_query_arg([
                            'order_by' => 'created_at',
                            'order' => $_order
                        ]);
                        ?>
                        <th data-priority="-1" class="hh-checkbox-td">
                            <div class="checkbox checkbox-success hh-check-all d-none d-md-block">
                                <input id="hh-checkbox-all--my-post" type="checkbox">
                                <label for="hh-checkbox-all--my-post"><span
                                        class="d-none">{{__('Check All')}}</span></label>
                            </div>
                        </th>
                        <th data-priority="1" class="force-show">
                            {{__('Name')}}
                        </th>
                        <th data-priority="3">
                            {{__('Categories')}}
                        </th>
                        <th data-priority="3">
                            {{__('Tags')}}
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
                                       href="{{ add_query_arg('status', 'publish') }}">{{__('Publish')}}</a>
                                    <a class="dropdown-item"
                                       href="{{ add_query_arg('status', 'draft') }}">{{__('Draft')}}</a>
                                </div>
                            </div>
                        </th>
                        <th data-priority="4">
                            <a href="{{ $url }}" class="order">
                                {{__('Created at')}}
                                @if($order == 'asc')
                                    <i class="icon-arrow-down"></i>
                                @else
                                    <i class="icon-arrow-up"></i>
                                @endif
                            </a>
                        </th>
                        <th data-priority="-1" class="text-center">{{__('Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($allPosts['total'])
                        @foreach ($allPosts['results'] as $item)
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
                                        <?php
                                        $img_url = get_attachment_url(get_translate($item->thumbnail_id), [70, 70]);
                                        $img_alt = get_attachment_alt(get_translate($item->thumbnail_id));
                                        ?>
                                        <img src="{{ $img_url }}" class="d-none d-md-block mr-3"
                                             alt="{{ $img_alt }}">
                                        <div class="media-body">
                                            <h5 class="m-0 title-overflow">
                                                <a class="text text-overflow" href="{{ get_the_permalink($item->post_id, $item->post_slug, 'post') }}"
                                                   target="_blank">
                                                    {{ get_translate($item->post_title) }}
                                                </a>
                                                <span class="text-muted d-none d-md-inline-block"> - {{ $item->post_id }}</span>
                                            </h5>
                                            <div class="quick-action-links d-none d-md-block">
                                                <a class="quick-link-item" target="_blank"
                                                   href="{{ url('dashboard/edit-post/' . $item->post_id ) }}">{{__('Edit')}}</a>
                                                @if($item->status != 'trash')
                                                    <?php
                                                    $data = [
                                                        'serviceID' => $item->post_id,
                                                        'serviceEncrypt' => hh_encrypt($item->post_id),
                                                        'status' => 'trash'
                                                    ];
                                                    ?>
                                                    <a class="quick-link-item quick-link-item-trash hh-link-action hh-link-change-post-status"
                                                       data-action="{{ dashboard_url('change-post-status') }}"
                                                       data-parent="tr"
                                                       data-is-delete="false"
                                                       data-params="{{ base64_encode(json_encode($data)) }}"
                                                       href="javascript: void(0)">{{ __('Trash') }}</a>
                                                    @if($item->status == 'publish')
                                                        <a class="quick-link-item"
                                                           href="{{ get_the_permalink($item->post_id, $item->post_slug, 'post') }}"
                                                           target="_blank">{{ __('View') }}</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <?php
                                    $categories = get_category($item->post_id);
                                    if (!empty($categories)) {
                                        $arr_cate = [];
                                        foreach ($categories as $k => $v) {
                                            array_push($arr_cate, '<a href="' . get_term_link($v->term_name) . '">' . esc_html(get_translate($v->term_title)) . '</a>');
                                        }
                                        echo implode(', ', $arr_cate);
                                    } else {
                                        echo '---';
                                    }
                                    ?>
                                </td>
                                <td class="align-middle">
                                    <?php
                                    $tags = get_tag($item->post_id);
                                    if (!empty($tags)) {
                                        $arr_tag = [];
                                        foreach ($tags as $k => $v) {
                                            array_push($arr_tag, '<a href="' . get_term_link($v->term_name, 'tag') . '">' . esc_html(get_translate($v->term_title)) . '</a>');
                                        }
                                        echo implode(', ', $arr_tag);
                                    } else {
                                        echo '---';
                                    }
                                    ?>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="service-status {{ $item->status }} status-icon"
                                         data-toggle="tooltip" data-placement="right" title=""
                                         data-original-title="{{ Illuminate\Support\Str::studly($item->status) }}"><span
                                            class="d-none">{{ Illuminate\Support\Str::studly($item->status) }}</span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <strong>{{ date(hh_date_format(), $item->created_at) }}</strong>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="dropdown d-inline-block">
                                        <?php
                                        $data = [
                                            'postID' => $item->post_id,
                                            'postEncrypt' => hh_encrypt($item->post_id),
                                        ];
                                        ?>
                                        <a href="javascript: void(0)" class="dropdown-toggle table-action-link"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                class="ti-settings"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ url('dashboard/edit-post/' . $item->post_id ) }}"
                                               class="dropdown-item">{{__('Edit')}}</a>
                                            <?php
                                            $page_status_info = post_status_info();
                                            ?>
                                            @foreach($page_status_info as $key => $status)
                                                @if($key != $item->status)
                                                    <?php
                                                    $data = [
                                                        'serviceID' => $item->post_id,
                                                        'serviceEncrypt' => hh_encrypt($item->post_id),
                                                        'status' => $key
                                                    ];
                                                    ?>
                                                    <a class="dropdown-item hh-link-action hh-link-change-status-post"
                                                       data-action="{{ dashboard_url('change-post-status') }}"
                                                       data-parent="tr"
                                                       data-is-delete="false"
                                                       data-params="{{ base64_encode(json_encode($data)) }}"
                                                       href="javascript: void(0)">{{ $status['name'] }}</a>
                                                @endif
                                            @endforeach
                                            <a class="dropdown-item hh-link-action hh-link-delete-post text-danger"
                                               data-action="{{ dashboard_url('delete-post-item') }}"
                                               data-parent="tr"
                                               data-is-delete="true"
                                               data-params="{{ base64_encode(json_encode($data)) }}"
                                               data-confirm="yes"
                                               data-confirm-title="{{__('Confirm Delete')}}"
                                               data-confirm-question="{{__('Are you sure want to delete this post?')}}"
                                               data-confirm-button="{{__('Delete it!')}}"
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
                            <td class="d-none"></td>
                            <td colspan="7">
                                <h4 class="mt-3 text-center">{{__('No posts yet.')}}</h4>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="clearfix mt-2">
                    {{ dashboard_pagination(['total' => $allPosts['total']]) }}
                </div>
            </div>
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')

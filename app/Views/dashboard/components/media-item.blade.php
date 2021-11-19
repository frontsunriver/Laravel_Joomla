<?php
extract($attachment);
$params = [
    'attachment_id' => $media_id,
    'attachment_encrypt' => hh_encrypt($media_id)
];
$media_url = get_attachment_url($media_id, [130, 130])
?>
@if(!isset($sort) || $sort == 'grid')
    <li>
        <div class="hh-media-item relative @if(isset($selected) && $selected) selected @endif" data-params="{{ base64_encode(json_encode($params)) }}"
             data-delete-url="{{ dashboard_url('delete-media-item') }}">
            <div class="hh-media-thumbnail">
                <img src="{{ $media_url }}" alt="{{ $media_description }}" class="img-fluid">
            </div>
            @if($type === 'normal')
                <a href="javascript:void(0)" class="link link-absolute"
                   data-attachment-id="{{ $media_id }}"
                   data-url="{{ $media_url }}">&nbsp;</a>
            @else
                <a href="javascript:void(0)" class="link link-absolute" data-toggle="modal"
                   data-target="#hh-media-item-modal"
                   data-attachment-id="{{ $media_id }}"
                   data-url="{{ dashboard_url('media-item-detail') }}">&nbsp;</a>
            @endif
        </div>
    </li>
@else
    <tr>
        <td class="align-middle hh-checkbox-td">
            <div class="checkbox checkbox-success">
                <input type="checkbox" name="post_id" value="{{$media_id}}" id="hh-check-all-item-{{$media_id}}"
                       class="hh-check-all-item">
                <label for="hh-check-all-item-{{$media_id}}"></label>
            </div>
        </td>
        <td class="align-middle">
            <div class="media align-items-center relative">
                <a href="javascript:void(0)" class="link link-absolute" data-toggle="modal"
                   data-target="#hh-media-item-modal"
                   data-attachment-id="{{ $media_id }}"
                   data-url="{{ dashboard_url('media-item-detail') }}"><span
                        class="d-none">{{ get_translate($media_title) }}</span></a>
                <img src="{{ $media_url }}" class="d-none d-md-block mr-3"
                     alt="{{ $media_description }}">
                <div class="media-body">
                    <h5 class="m-0">{{ get_translate($media_title) }}
                        <span class="text-muted"> - {{ $media_id }}</span>
                    </h5>
                </div>
            </div>
        </td>
        <td class="align-middle">
            {{get_file_size($media_size)}}/{{$media_type}}
        </td>
        <td class="align-middle">
            {{ get_username($author) }}
        </td>
        <td class="align-middle">
            {{ date(hh_date_format(), $created_at) }}
        </td>
        <td class="align-middle text-center">
            <div class="dropdown dropleft">
                <a href="javascript: void(0)" class="dropdown-toggle table-action-link"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                        class="ti-settings"></i></a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" target="_blank" data-toggle="modal"
                       data-target="#hh-media-item-modal"
                       data-attachment-id="{{ $media_id }}"
                       data-url="{{ dashboard_url('media-item-detail') }}"
                       href="javascript:void(0)">{{__('View')}}</a>
                    <?php
                    $data = [
                        'attachment_id' => $media_id,
                        'attachment_encrypt' => hh_encrypt($media_id),
                    ];
                    ?>
                    <a class="dropdown-item hh-link-action hh-link-delete-media"
                       data-action="{{ dashboard_url('delete-media-item') }}"
                       data-parent="tr"
                       data-is-delete="true"
                       data-confirm="yes"
                       data-confirm-title="{{__('System Alert')}}"
                       data-confirm-question="{{__('Are you sure want to delete this media?')}}"
                       data-confirm-button="{{__('Delete it!')}}"
                       data-params="{{ base64_encode(json_encode($data)) }}"
                       href="javascript: void(0)">{{__('Delete')}}
                    </a>
                </div>
            </div>
        </td>
    </tr>
@endif

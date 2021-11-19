<div class="row">
    <div class="col-12 col-sm-6 col-md-7">
        <div class="hh-media-detail-thumbnail">
            <img src="{{ get_attachment_url($mediaObject) }}" alt="{{ $mediaObject->media_description }}"
                 class="img-fluid">
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-5">
        <div class="hh-media-detail-description d-none d-md-block">
            <p><strong>{{__('ID')}}:</strong> {{ $mediaObject->media_id }}</p>
            <p><strong>{{__('File name')}}:</strong> {{ $mediaObject->media_title }}</p>
            <p><strong>{{__('File type')}}:</strong> {{ $mediaObject->media_type }}</p>
            <p><strong>{{__('File size')}}:</strong> {{ get_file_size($mediaObject->media_size) }}</p>
            <p><strong>{{__('Uploaded on')}}:</strong> {{ date('Y-m-d', $mediaObject->created_at) }}</p>
        </div>
        <div class="hh-media-detail-edit form-xs mt-3">
            <table class="table table-borderless">
                <tr>
                    <th class="align-middle">{{__('Title')}}</th>
                    <td class="align-middle">
                        <div class="form-group">
                            <input type="text" class="form-control" name="media_title"
                                   value="{{ $mediaObject->media_title }}">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">{{__('Alternative Text')}}</th>
                    <td class="align-middle">
                        <div class="form-group">
                            <input type="text" class="form-control" name="media_description"
                                   value="{{ $mediaObject->media_description }}">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="align-middle">{{__('Uploaded By')}}</th>
                    <td class="align-middle">
                        {{ get_username($mediaObject->author) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="media_id" value="{{ $mediaObject->media_id }}">

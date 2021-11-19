<?php
$layout = (!empty($layout)) ? $layout : 'col-12';
if (empty($value)) {
    $value = $std;
}
$idName = str_replace(['[', ']'], '_', $id);

global $post;

enqueue_style('dropzone-css');
enqueue_script('dropzone-js');

enqueue_style('confirm-css');
enqueue_script('confirm-js');
if (empty($style)) {
    $style = [450, 320];
}
?>
<div id="setting-{{ $idName }}" data-condition="{{ $condition }}"
     data-unique="{{ $unique }}"
     data-operator="{{ $operation }}"
     class="form-group mb-3 col {{ $layout }} field-{{ $type }}">
    <div class="hh-upload-wrapper" data-action-set-featured="{{ dashboard_url('set-featured-image') }}">
        <div class="hh-dashboard-upload-area">
            <h2>{{__('Select photo')}}</h2>
            <button class="btn btn-upload-media"
                    data-url="{{ dashboard_url('all-media') }}">
                {{__('Browse Image')}}
            </button>
            <div class="desc">
                {{ __($desc) }}
            </div>
            <input type="hidden" name="{{ $id }}" class="upload_value input-advance-uploads"
                   data-post-id="{{ $post_id }}" data-url="{{ dashboard_url('get-advance-attachments') }}"
                   data-post-type="{{ $post_type }}"
                   data-style="{{ implode(',', $style) }}"
                   id="{{ $idName }}" value="{{ $value }}">
        </div>
        <div class="hh-dashboard-upload-render row">
            <?php
            if (!empty($value)) {
            $gallery = explode(',', $value);
            $isFetured = $post->thumbnail_id;
            foreach ($gallery as $_id) {
            $img = get_attachment_url($_id);
            $classFeatured = ($_id == $isFetured) ? 'is-featured' : '';
            ?>
            <div class="col-6 col-md-3 item">
                <div class="gallery-item">
                    @include('common.loading', ['class' => 'loading-gallery'])
                    <div class="gallery-image">
                        <div class="gallery-action">
                            <a href="javascript: void(0)"
                               title="{{__('set is featured')}}"
                               class="hh-gallery-add-featured {{ $classFeatured }}"
                               data-post-id="{{ $post_id }}"
                               data-post-type="{{ $post_type }}"
                               data-style="{{ implode(',', $style) }}"
                               data-id="{{ $_id }}"><i class="fe-bookmark"></i></a>
                            <a href="javascript: void(0)" class="hh-gallery-delete"
                               title="{{__('Delete')}}"
                               data-post-id="{{ $post_id }}"
                               data-id="{{ $_id }}"><i class="dripicons-trash"></i></a>
                        </div>
                        <img src="{{ $img }}" alt="<?php echo get_attachment_alt($_id) ?>"
                             class="img-responsive">
                    </div>
                </div>
            </div>
            <?php
            }
            }
            ?>
        </div>
    </div>
</div>

@if($break)
    <div class="w-100"></div> @endif

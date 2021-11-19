<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function assignDataByUserID($user_id, $user_assign)
    {
        $model = new Media();
        $model->updateAuthor(['author' => $user_assign], $user_id);
    }

    public function _mediaBulkActions(Request $request)
    {
        $media_ids = request()->get('post_id');
        $action = request()->get('action', '');
        if (!empty($action) && !empty($media_ids)) {
            $media_ids = explode(',', $media_ids);
            switch ($action) {
                case 'delete':
                default:
                    foreach ($media_ids as $media_id) {
                        $media_id = (int)$media_id;
                        $responsive = $this->deleteAttachment($media_id);
                        if ($responsive['status']) {
                            continue;
                        } else {
                            $this->sendJson([
                                'status' => 0,
                                'title' => __('System Alert'),
                                'message' => sprintf(__('Error when delete: [ID %s]'), $media_id)
                            ], true);
                        }
                    }
                    break;
            }
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Bulk action successfully')
            ], true);
        }
        $this->sendJson([
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Data is invalid')
        ], true);
    }

    public function _getInlineMedia(Redirect $redirect)
    {
        $attachment_id = (int)request()->get('attachment_id');
        if (!$attachment_id) {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Can not get this image')
            ]);
        }
        $image_url = get_attachment_url($attachment_id, 'full');
        return $this->sendJson([
            'status' => 1,
            'title' => __('System Alert'),
            'message' => __('Successfully get image'),
            'html' => '<figure class="image"><img src="' . e($image_url) . '" class="img-inline" alt="' . get_attachment_alt($attachment_id) . '"/></figure>'
        ]);
    }

    public function _getAttachments(Request $request)
    {
        $attachments = request()->get('attachments');
        $attachments = explode(',', $attachments);
        $size = request()->get('size', 'full');
        if (is_numeric($size)) {
            $size = [$size, $size];
        } elseif (strpos(',', $size) !== FALSE) {
            $size = explode(',', $size);
            if (count($size) == 1) {
                $size[] = $size[0];
            }
        }
        $html = '';
        $url = [];
        if (!empty($attachments)) {
            foreach ($attachments as $attachment_id) {
                $attachment = get_attachment_info($attachment_id, $size);
                if ($attachment) {
                    $url[] = $attachment['url'];
                    $html .= '<div class="attachment-item"><div class="thumbnail"><img src="' . esc_attr($attachment['url']) . '" alt="' . esc_attr($attachment['description']) . '"></div></div>';
                }

            }
        }

        $this->sendJson([
            'status' => 1,
            'html' => $html,
            'url' => $url
        ], true);
    }

    public function _getAdvanceAttachments(Request $request)
    {
        $attachments = request()->get('attachments');
        $attachments = explode(',', $attachments);
        $post_type = request()->get('post_type', 'home');
        $size = explode(',', request()->get('size', '450,320'));
        $html = '';
        $isFeatured = '';
        if (!empty($attachments) && is_array($attachments)) {
            $postID = request()->get('postID');
            $post = get_post($postID, $post_type);
            $isFeatured = $post->thumbnail_id;
            foreach ($attachments as $id) {
                if (!$isFeatured) {
                    set_post_thumbnail($postID, $id, $post_type);
                    $isFeatured = $id;
                }
                $img = get_attachment_url($id);
                $classFeatured = ($id == $isFeatured) ? 'is-featured' : '';
                $html .= '<div class="col-6 col-md-3 item"><div class="gallery-item">
                    <div class="gallery-image">
                        <div class="hh-loading ">
                            <div class="lds-ellipsis loading-gallery">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                        <div class="gallery-action">
                            <a href="javascript: void(0)" class="hh-gallery-add-featured ' . esc_attr($classFeatured) . '" data-style="' . esc_attr(implode(',', $size)) . '" data-post-type="' . esc_attr($post_type) . '" data-post-id="' . esc_attr($postID) . '" data-id="' . esc_attr($id) . '" title="set is featured"><i class="fe-bookmark"></i></a>
                            <a href="javascript: void(0)" class="hh-gallery-delete" data-post-id="' . esc_attr($postID) . '" data-id="' . esc_attr($id) . '" title="' . __('Delete') . '"><i class="dripicons-trash"></i></a>
                        </div>
                        <img src="' . esc_attr($img) . '" alt="' . esc_attr(get_attachment_alt($id)) . '"
                             class="img-responsive">
                    </div>
                </div></div>';
            }
        }

        $this->sendJson([
            'status' => 1,
            'html' => $html,
            'featured_image' => get_attachment_url($isFeatured, $size)
        ], true);
    }

    public function _updateMediaItemDetail(Request $request)
    {
        $attachment_id = request()->get('media_id');
        $media_title = request()->get('media_title');
        $media_description = request()->get('media_description');

        $media = new Media();
        $mediaObject = $media->issetAttachment($attachment_id);
        if ($mediaObject) {
            $data = [
                'media_title' => $media_title,
                'media_description' => $media_description
            ];
            $updated = $media->updateMedia($data, $attachment_id);
            if (!is_null($updated)) {
                $this->sendJson([
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Updated successfully')
                ], true);
            } else {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Can not update this attachment')
                ], true);
            }
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Not found this attachment')
            ], true);
        }
    }

    public function _mediaItemDetail(Request $request)
    {
        $attachment_id = request()->get('attachment_id');
        $media = new Media();
        $mediaObject = $media->getById($attachment_id);
        if (!empty($mediaObject) && is_object($mediaObject)) {
            $html = view('dashboard.components.media-item-detail', ['mediaObject' => $mediaObject])->render();
            $this->sendJson([
                'status' => 1,
                'message' => __('Loaded successfully'),
                'html' => $html
            ], true);
        }

        $this->sendJson([
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Not found this attachment'),
        ], true);
    }

    public function _allMedia(Request $request)
    {
        $type = request()->get('type', '');
        $sort = request()->get('sort', 'grid');
        $page = request()->get('page', 1);
        $number = request()->get('number', -1);
        $search = request()->get('search', '');
        $attachment_ids = request()->get('attachment_ids', '');
        if (!empty($attachment_ids)) {
            $attachment_ids = array_filter(explode(',', $attachment_ids), function ($value) {
                return (int)$value > 0;
            });
        }

        $args = [
            'number' => (int)$number,
            'page' => (int)$page,
            's' => esc_sql($search)
        ];

        if (is_array($attachment_ids)) {
            $args['not_in'] = $attachment_ids;
        }
        $media = new Media();
        $allMedia = $media->listAttachments($args);

        $html = '';
        if ($page == 1 && is_array($attachment_ids)) {
            foreach ($attachment_ids as $attachment_id) {
                $attachment = get_attachment($attachment_id);
                if (!is_null($attachment)) {
                    $attachment = (array)$attachment;
                    $attachment['type'] = $type;
                    $html .= view('dashboard.components.media-item', ['attachment' => $attachment, 'sort' => $sort, 'selected' => true])->render();
                }
            }
        }
        if (!is_null($allMedia)) {
            foreach ($allMedia as $key => $attachment) {
                $attachment = (array)$attachment;
                $attachment['type'] = $type;
                $html .= view('dashboard.components.media-item', ['attachment' => $attachment, 'sort' => $sort])->render();
            }
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Loaded Media'),
                'html' => $html,
                'page' => (int)$page + 1
            ], true);
        } else {
            if (!empty($html)) {
                $this->sendJson([
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Loaded Media'),
                    'html' => $html,
                    'page' => (int)$page
                ], true);
            } else {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Not found media'),
                    'html' => $html,
                    'page' => (int)$page
                ], true);
            }

        }
    }

    public function _addMedia(Request $request)
    {
        $file = $request->file('file');
        if (is_object($file) && $file->getError() === 0) {
            $media_permissions = get_media_upload_permission();
            $file_mime = $file->getClientMimeType();
            if (!in_array($file_mime, $media_permissions)) {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => get_media_upload_message()['type']
                ], true);
            }
            $media_max_size = get_media_upload_size() * 1048576;
            $file_size = $file->getSize();
            if ($file_size > $media_max_size) {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => get_media_upload_message()['size']
                ], true);
            }

            $origin_name = $file->getClientOriginalName();
            $file_extension = $file->getClientOriginalExtension();

            $file_name = esc_html(pathinfo($origin_name, PATHINFO_FILENAME));

            $slug_name = Str::slug($file_name);
            $saved_ame = $slug_name . '-' . time() . '.' . $file_extension;

            $new_file = $file->move(storage_path($this->getMediaFolder()), $saved_ame);

            if (is_object($new_file) && $new_file->isFile()) {
                $tinypng_enable = get_opt('tinypng_enable', 'off');
                if ($tinypng_enable == 'on') {
                    try {
                        \Tinify\setKey(get_opt('tinypng_api_key', ''));
                        \Tinify\validate();
                        $source = \Tinify\fromFile($new_file->getPathname());
                        $source->toFile($new_file->getPathname());
                    } catch (\Tinify\Exception $e) {
                        $this->sendJson([
                            'status' => 0,
                            'title' => __('System Alert'),
                            'message' => $e->getMessage()
                        ], true);
                    }
                }
                $data = [
                    'media_title' => $file_name,
                    'media_name' => $slug_name,
                    'media_url' => $this->get_media_url($saved_ame),
                    'media_path' => $this->get_media_path($saved_ame),
                    'media_size' => $file_size,
                    'media_type' => $file_extension,
                    'media_description' => $file_name,
                    'author' => get_current_user_id(),
                    'created_at' => time()
                ];

                $media = new Media();
                $media_id = $media->create($data);
                if ($media_id) {
                    $this->sendJson([
                        'status' => 2,
                        'title' => __('System Alert'),
                        'message' => sprintf(__('The attachment %s is uploaded successfully'), $file_name),
                    ], true);
                } else {
                    $this->sendJson([
                        'status' => 0,
                        'title' => __('System Alert'),
                        'message' => __('Have error when saving')
                    ], true);
                }
            } else {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Have error when uploading')
                ], true);
            }
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This file is invalid')
            ], true);
        }
    }

    public function get_media_url($media_name): string
    {
        return $this->getMediaFolder(true, true) . '/' . $media_name;
    }

    public function get_media_path($media_name): string
    {
        return $this->getMediaFolder(false, true) . '/' . $media_name;
    }

    public function _deleteMediaItem(Request $request)
    {
        $attachment_id = request()->get('attachment_id');

        $responsive = $this->deleteAttachment($attachment_id);
        if ($responsive['status']) {
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => $responsive['message']
            ], true);
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => $responsive['message']
            ], true);
        }
    }

    public function deleteAttachment($attachment_id)
    {
        $current_user_id = get_current_user_id();

        $media = new Media();
        $mediaObject = $media->getById($attachment_id);

        if (is_null($mediaObject)) {
            return [
                'status' => 0,
                'message' => __('This attachment is unavailable')
            ];
        }
        if (!is_admin() && $current_user_id != $mediaObject->author) {
            return [
                'status' => 0,
                'message' => __('Can not access this attachment')
            ];
        }

        $path = base_path($mediaObject->media_path);

        if (!file_exists($path)) {
            $media->deleteAttachment($attachment_id);

            return [
                'status' => 1,
                'message' => __('This attachment is not stored')
            ];
        }

        $all_sizes[] = $path;

        $path_info = pathinfo($path);
        $file_name = $path_info['filename'];
        $dir_name = $path_info['dirname'];
        $file_type = $path_info['extension'];

        $crop_image_sizes = crop_image_sizes();

        if (!empty($crop_image_sizes)) {
            foreach ($crop_image_sizes as $size) {
                $all_sizes[] = $dir_name . '/' . $file_name . '-' . $size[0] . 'x' . $size[1] . '.' . $file_type;
            }
        }

        File::delete($all_sizes);

        $deleted = $media->deleteAttachment($attachment_id);

        if ($deleted) {
            return [
                'status' => 1,
                'message' => __('Deleted successfully')
            ];
        } else {
            return [
                'status' => 0,
                'message' => __('Can not delete this attachment')
            ];
        }
    }

    public function _getMedia()
    {
        $folder = $this->getFolder();
        return view("dashboard.screens.{$folder}.media", ['role' => $folder, 'bodyClass' => 'hh-dashboard']);
    }

    public function getMediaFolder($assets = false, $include_storage_folder = false)
    {
        $user = Sentinel::getUser();
        $user_id = $user->getUserId();
        $year = date('Y');
        $month = date('m');
        $day = date('d');

        $storage_folder = $include_storage_folder ? 'storage' : '';

        if ($assets) {
            return $storage_folder . '/u-' . $user_id . '/' . $year . '/' . $month . '/' . $day;
        } else {
            return $storage_folder . '/app/public/u-' . $user_id . '/' . $year . '/' . $month . '/' . $day;
        }
    }

    public static function get_inst()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}

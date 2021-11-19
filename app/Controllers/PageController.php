<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Sentinel;
use Str;


class PageController extends Controller
{
    public function __construct()
    {
        add_action('hh_dashboard_breadcrumb', [$this, '_addCreatePageButton']);
    }

    public function _changePageStatus(Request $request)
    {
        $post_id = request()->get('serviceID');
        $post_encrypt = request()->get('serviceEncrypt');
        $status = request()->get('status', '');

        if (!hh_compare_encrypt($post_id, $post_encrypt) || !$status) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('The data is invalid')
            ], true);
        }

        $model = new Page();
        $updated = $model->updatePage([
            'status' => $status
        ], $post_id);

        if ($updated) {
            $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Updated Successfully'),
                'reload' => true
            ], true);
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Have error when saving')
            ], true);
        }
    }

    public function _bulkActions(Request $request)
    {
        $action = request()->get('action', '');
        $post_id = request()->get('post_id', '');

        if (!empty($action) && !empty($post_id)) {
            $post_id = explode(',', $post_id);

            $pageModel = new Page();
            switch ($action) {
                case 'delete':
                    $pageModel->whereIn('post_id', $post_id)->delete();
                    break;
                case 'publish':
                case 'trash':
                case 'draft':
                    $pageModel->updateMultiPage([
                        'status' => $action
                    ], $post_id);
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
            'message' => __('Data invalid')
        ], true);
    }

    public function viewPage(Request $request, $page_id, $page_slug = null)
    {
        global $post;
        $post = $this->getById($page_id, true);
        if (is_null($post) || !$post || $post->status != 'publish') {
            return view('frontend.404');
        } else {
            return view('frontend.page.default');
        }
    }

    public function getById($page_id, $global = false)
    {
        $page_object = new Page();
        $post_item = $page_object->getById($page_id);
        if (!is_null($post_item)) {
            $post_item = $this->setup_post_data($post_item);
        }
        if ($global) {
            global $post;
            $post = $post_item;
        }

        return $post_item;
    }

    public function getByName($page_name, $global = false)
    {
        $page_object = new Page();
        $post_item = $page_object->getByName($page_name);
        if (!is_null($post_item)) {
            $post_item = $this->setup_post_data($post_item);
        }
        if ($global) {
            global $post;
            $post = $post_item;
        }

        return $post_item;
    }


    public function setup_post_data($post)
    {
        return $this->_storeData($post);
    }

    private function _storeData($post)
    {
        // can add more data for $post

        return $post;
    }

    public function _deletePageAction(Request $request)
    {
        $post_id = request()->get('serviceID');
        $page_encrypt = request()->get('serviceEncrypt');
        $delete_type = request()->get('type');
        if (!hh_compare_encrypt($post_id, $page_encrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This page is invalid')
            ], true);
        }
        $page = new Page();
        $pageObject = $page->getById($post_id);

        if (!empty($pageObject) && is_object($pageObject)) {
            $deleted = $page->deletePage($post_id);

            if ($deleted) {
                $res = [
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('This Page is deleted')
                ];
                if ($delete_type == 'in-page') {
                    $res['redirect'] = dashboard_url('all-page');
                } else {
                    $res['reload'] = true;
                }
                $this->sendJson($res, true);
            } else {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Can not delete this page')
                ], true);
            }
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This Page is invalid')
            ], true);
        }
    }

    public function _editPageAction()
    {
        $post_id = request()->get('postID');
        $page_encrypt = request()->get('postEncrypt');
        $action = request()->get('action', 'edit');

        if (!hh_compare_encrypt($post_id, $page_encrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This page is invalid')
            ], true);
        }
        $page = new Page();
        $pageObject = $page->getById($post_id);

        if (!empty($pageObject) && is_object($pageObject)) {
            $post_title = set_translate('post_title');
            $post_content = set_translate('post_content');
            $page_template = request()->get('page_template', 'default');
            $thumbnail_id = request()->get('thumbnail_id');
            $post_status = request()->get('post_status');
            $post_slug = \Illuminate\Support\Str::slug(request()->get('post_slug'));

            $post_title_field = 'post_title';
            if (is_multi_language()) {
                $current_lang = get_current_language();
                $post_title_field .= '_' . $current_lang;
            }

            if ($post_title) {
                $data = [
                    'post_title' => $post_title,
                    'post_content' => $post_content,
                    'post_slug' => $post_slug,
                    'thumbnail_id' => $thumbnail_id,
                    'page_template' => $page_template,
                    'status' => $post_status
                ];

                if (isset($_POST['post_slug']) && empty($data['post_slug'])) {
                    $data['post_slug'] = \Illuminate\Support\Str::slug(esc_html(request()->get($post_title_field, 'new-post-' . time())));
                }

                $updated = $page->updatePage($data, $post_id);

                if (!is_null($updated)) {
                    $response = [
                        'status' => 1,
                        'title' => __('System Alert'),
                        'message' => __('Updated Successfully'),
                    ];
                    if ($action == 'add-new') {
                        $response['redirect'] = dashboard_url('edit-page/' . $post_id);
                    } else {
                        $response['reload'] = true;
                    }
                    $this->sendJson($response, true);
                } else {
                    $this->sendJson([
                        'status' => 0,
                        'title' => __('System Alert'),
                        'message' => __('Can not update this page')
                    ], true);
                }
            } else {
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Some fields is incorrect')
                ], true);
            }

        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This page is invalid')
            ], true);
        }
    }

    public function _editPage(Request $request, $id = null)
    {
        $folder = $this->getFolder();
        $page = new Page();
        $pageObject = $page->getById($id);
        if (is_object($pageObject) && user_can_edit_post($pageObject)) {
            return view("dashboard.screens.{$folder}.services.page.edit-page", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'newPage' => $id]);

        } else {
            return redirect()->to(dashboard_url('/'));
        }
    }

    public function _addCreatePageButton()
    {
        $screen = current_screen();
        if ($screen == 'all-page') {
            echo view('dashboard.components.add-page')->render();
        }
    }

    public function _allPage($page = 1)
    {
        $folder = $this->getFolder();
        $search = request()->get('_s');
        $orderBy = request()->get('orderby', 'post_id');
        $order = request()->get('order', 'desc');
        $status = request()->get('status', '');

        $pageModel = new Page();

        $allPages = $pageModel->getAllPages(
            [
                'search' => $search,
                'page' => $page,
                'orderby' => $orderBy,
                'order' => $order,
                'status' => $status
            ]
        );
        return view("dashboard.screens.{$folder}.services.page.page", ['bodyClass' => 'hh-dashboard', 'allPages' => $allPages]);
    }

    public function _addNewPage()
    {
        $folder = $this->getFolder();
        $page = new Page();
        $newPage = $page->createPage();

        return view("dashboard.screens.{$folder}.services.page.add-new-page", ['role' => $folder, 'bodyClass' => 'hh-dashboard', 'newPage' => $newPage]);
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

<?php

namespace App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Sentinel;
use Input;
use Str;
use App\Models\Menu;
use App\Models\MenuStructure;

class MenuController extends Controller
{
	public function deleteMenuAction(){

		$menu_id = request()->get('menuID');
        $page_encrypt = request()->get('menuEncrypt');

        if (!hh_compare_encrypt($menu_id, $page_encrypt)) {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This menu is invalid')
            ], true);
        }

        $menu = new Menu();
		$menuStructure = new MenuStructure();

        $menuObject = $menu->getById($menu_id);

        if (!empty($menuObject) && is_object($menuObject)) {
            $deleted = $menu->deleteMenu($menu_id);
			$deleted_structure = $menuStructure->deleteItemByMenuId($menu_id);

            if($deleted){
                $this->sendJson([
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('This Menu is deleted'),
                    'redirect' => dashboard_url('menus')
                ], true);
            }else{
                $this->sendJson([
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Can not delete this menu')
                ], true);
            }
        } else {
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('This Menu is invalid')
            ], true);
        }

	}

	private function updateMenuStructure($menu_id, $menu_structure){
		$menuStructure = new MenuStructure();
		$menuStructure->resetMenuStructure($menu_id);

		if(!empty($menu_structure)){
			foreach ($menu_structure as $k => $v) {
				$data = [
					'item_id' => $v->item_id,
					'parent_id' => $v->parent_id,
					'depth' => $v->depth,
					'left' => $v->left,
					'right' => $v->right,
					'name' => isset($v->name) ? $v->name : '',
					'type' => isset($v->type) ? $v->type : '',
					'post_id' => isset($v->post_id) ? $v->post_id : '',
					'post_title' => isset($v->post_title) ? $v->post_title : '',
					'url' => isset($v->url) ? $v->url : '',
					'class' => isset($v->class) ? $v->class : '',
					'menu_id' => $menu_id,
                    'menu_lang' => get_current_language(),
                    'target_blank' => isset($v->target_blank) ? $v->target_blank : 0,
					'created_at' => time()
				];

				$menuStructure->createMenuItem($data);
			}
		}
	}

	public function updateMenuAction(){

		$menu_id = request()->get('menu_id');
		$menu_name = request()->get('menu_name');
		$menu_location = request()->get('menu_location');
		$menu_structure = request()->get('menu_structure');

		if(!empty($menu_name)) {

            $menu_structure = json_decode($menu_structure);

            $menu = new Menu();

            if (!empty($menu_location)) {
                $menu->resetMenuLocation($menu_location);
            }

            if (empty($menu_id)) {
                $data = [
                    'menu_title' => $menu_name,
                    'menu_position' => $menu_location,
                    'created_at' => time()
                ];

                $insert_menu = $menu->createMenu($data);

                if (!empty($menu_structure)) {
                    $this->updateMenuStructure($insert_menu, $menu_structure);
                }

                $this->sendJson([
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Created menu successfully'),
                    'redirect' => url('dashboard/menus?menu_id=' . $insert_menu)
                ], true);
            } else {
                $data = [
                    'menu_title' => $menu_name,
                    'menu_position' => $menu_location,
                ];

                $menu->updateMenu($menu_id, $data);

                if (!empty($menu_structure)) {
                    $this->updateMenuStructure($menu_id, $menu_structure);
                }

                $this->sendJson([
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => __('Updated menu successfully'),
                    'redirect' => url('dashboard/menus?menu_id=' . $menu_id)
                ], true);
            }
        }else{
            $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Please create new menu before doing it'),
                'redirect' => url('dashboard/menus?menu_id=' . $menu_id)
            ], true);
        }
	}

    public function index()
    {
	    $folder = $this->getFolder();
	    return view("dashboard.screens.{$folder}.menu", ['bodyClass' => 'hh-dashboard']);
    }

}

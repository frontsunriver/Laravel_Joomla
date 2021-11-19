<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class MenuStructure extends Model
{
    protected $table = 'menu_structure';
    protected $primaryKey = 'id';

    public function getByMenuId($menu_id)
    {
        $menus = DB::table($this->table)->where('menu_id', $menu_id)->where('menu_lang', get_current_language())->get();
        return $menus;
    }

    public function resetMenuStructure($menu_id){
        if(is_multi_language()) {
            return DB::table($this->table)->where('menu_id', $menu_id)->where('menu_lang', get_current_language())->delete();
        }else{
            return DB::table($this->table)->where('menu_id', $menu_id)->delete();
        }
    }

    public function deleteItemByMenuId($menu_id){
        return DB::table($this->table)->where('menu_id', $menu_id)->delete();
    }

    public function deletePage($post_id)
    {
        return DB::table($this->table)->where('post_id', $post_id)->delete();
    }

    public function updateMenu($menu_id, $data)
    {
        return DB::table($this->getTable())->where('menu_id', $menu_id)->update($data);
    }

    public function getById($menu_id)
    {
        $menu = DB::table($this->table)->where('menu_id', $menu_id)->get()->first();
        return $menu;
    }

    public function createMenuItem($data = [])
    {
        return DB::table($this->getTable())->insertGetId($data);
    }

    public function getAllMenus($data = [])
    {
       $sql = DB::table($this->getTable())->select();
       $results = $sql->get();
       return (!empty($results) && is_object($results)) ? $results : false;
    }
}

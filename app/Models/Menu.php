<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'menu_id';

    public function resetMenuLocation($menu_location){
        return DB::table($this->getTable())->where('menu_position', $menu_location)->update([
            'menu_position' => ''
        ]);
    }

    public function deleteMenu($menu_id)
    {
        return DB::table($this->table)->where('menu_id', $menu_id)->delete();
    }

    public function updateMenu($menu_id, $data)
    {
        return DB::table($this->getTable())->where('menu_id', $menu_id)->update($data);
    }

    public function hasMenuLocation($menu_type = 'primary'){
        $count = DB::table($this->getTable())->selectRaw('COUNT(*) as row_count')->where('menu_position', $menu_type)->get()[0]->row_count;
        if($count > 0){
            return true;
        }
        return false;
    }

    public function getByWhere($where)
    {
        $menu = DB::table($this->table)->where($where)->get();
        return $menu;
    }

    public function getById($menu_id)
    {
        $menu = DB::table($this->table)->where('menu_id', $menu_id)->get()->first();
        return $menu;
    }

    public function getMenuByLocation($location){
        $menu = DB::table($this->table)->where('menu_position', $location)->get()->first();
        return $menu;
    }

    public function createMenu($data = [])
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

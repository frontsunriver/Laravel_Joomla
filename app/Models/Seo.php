<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class Seo extends Model
{
    protected $table = 'seo';
    protected $primaryKey = 'seo_id';

    public function getById($seo_id)
    {
        $seo = DB::table($this->table)->where('seo_id', $seo_id)->get()->first();
        return (!empty($seo) && is_object($seo)) ? $seo : null;
    }

    public function getByPostId($post_id, $post_type = 'post')
    {
        $seo = DB::table($this->table)->where('post_id', $post_id)->where('post_type', $post_type);
        $result = $seo->get()->first();
        return (!empty($result) && is_object($result)) ? $result : null;
    }

    public function updateSeo($data, $seo_id)
    {
        $update = DB::table($this->getTable())->where('seo_id', $seo_id)->update($data);
        return $update !== false ? $seo_id : false;
    }

    public function createSeo($data = [])
    {
        return DB::table($this->getTable())->insertGetId($data);
    }
}

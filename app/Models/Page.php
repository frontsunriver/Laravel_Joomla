<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class Page extends Model
{
    protected $table = 'page';
    protected $primaryKey = 'post_id';


    public function getById($id)
    {
        $post = DB::table($this->table)->where('post_id', $id)->get()->first();
        return (!empty($post) && is_object($post)) ? $post : null;
    }

    public function getByName($slug)
    {
        $post = DB::table($this->table)->where('post_slug', $slug)->get()->first();
        return (!empty($post) && is_object($post)) ? $post : null;
    }

    public function deletePage($id)
    {
        return DB::table($this->table)->where('post_id', $id)->delete();
    }

    public function updateMultiPage($data, $ids)
    {
        return DB::table($this->getTable())->whereIn('post_id', $ids)->update($data);
    }

    public function updatePage($data, $id)
    {
        return DB::table($this->getTable())->where('post_id', $id)->update($data);
    }

    public function createPage($data = [])
    {
        $time = time();
        $default = [
            'post_title' => sprintf(__('New Page - %s'), $time),
            'post_slug' => sprintf(__('new-page-%s'), $time),
            'created_at' => $time,
            'author' => get_current_user_id(),
            'status' => 'revision'
        ];

        $data = wp_parse_args($data, $default);

        return DB::table($this->getTable())->insertGetId($data);
    }

    public function getAllPages($data = [])
    {
        $default = [
            'search' => '',
            'page' => 1,
            'orderby' => 'post_id',
            'order' => 'desc',
            'status' => [],
            'number' => posts_per_page()
        ];
        $data = wp_parse_args($data, $default);

        $number = $data['number'];

        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS page.*")
            ->orderBy($data['orderby'], $data['order']);
        if ($number != -1) {
            $offset = ($data['page'] - 1) * $number;
            $sql->limit($number)->offset($offset);
        }
        if (!empty($data['search'])) {
            $data['search'] = esc_sql($data['search']);
            $search = esc_sql($data['search']);
            $sql->whereRaw("page.post_id = '{$search}' OR page.post_title LIKE '%{$search}%'");
        }

        if (!empty($data['status']) && is_array($data['status'])) {
            $sql->whereIn('page.status', $data['status']);
        } else {
            $sql->whereNotIn('page.status', ['revision']);
        }

        $results = $sql->get();

        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];

    }
}

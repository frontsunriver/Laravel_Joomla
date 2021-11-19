<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class Post extends Model
{
    protected $table = 'post';
    protected $primaryKey = 'post_id';

    public function getBySlug($post_slug, $status = '')
    {
        $query = DB::table($this->table)->where('post_slug', $post_slug);
        if (!empty($status)) {
            if (in_array($status, ['publish', 'draft'])) {
                $query->where('status', $status);
            }
        }
        return $query->get()->first();
    }

    public function getTermByPostID($post_id, $tax = 'post-category')
    {
        $result = DB::select("SELECT * FROM term_relation INNER JOIN term ON term_relation.term_id  = term.term_id INNER JOIN taxonomy ON term.taxonomy_id = taxonomy.taxonomy_id WHERE service_id = {$post_id} AND taxonomy_name = '{$tax}'");

        return $result;
    }

    public function deletePost($post_id)
    {
        DB::table('term_relation')->where('service_id', $post_id)->where('post_type', 'post')->delete();
        DB::table('comments')->where('post_id', $post_id)->where('post_type', 'posts')->delete();
        return DB::table($this->table)->where('post_id', $post_id)->delete();
    }

    public function updateMultiPost($data, $post_id)
    {
        return DB::table($this->getTable())->whereIn('post_id', $post_id)->update($data);
    }

    public function updatePost($data, $post_id)
    {
        return DB::table($this->getTable())->where('post_id', $post_id)->update($data);
    }

    public function getById($home_id)
    {
        $post = DB::table($this->table)->where('post_id', $home_id)->get()->first();
        return (!empty($post) && is_object($post)) ? $post : null;
    }

    public function getByName($home_name)
    {
        $post = DB::table($this->table)->where('post_slug', $home_name)->get()->first();
        return (!empty($post) && is_object($post)) ? $post : null;
    }

    public function createPost($data = []): int
    {
        $default = [
            'post_title' => 'New Post - ' . time(),
            'post_slug' => 'new-post-' . time(),
            'post_content' => '',
            'thumbnail_id' => '',
            'created_at' => time(),
            'author' => get_current_user_id(),
            'status' => 'revision'
        ];

        $data = wp_parse_args($data, $default);

        return DB::table($this->getTable())->insertGetId($data);
    }

    public function getAllPosts($data = [])
    {
        $default = [
            'search' => '',
            'page' => 1,
            'order_by' => 'post_id',
            'order' => 'desc',
            'number' => posts_per_page(),
            'term_slug' => '',
            'term_ids' => '',
            'term_relation' => 'and',
            'status' => ''
        ];
        $data = wp_parse_args($data, $default);

        $number = $data['number'];

        $query = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS post.*")
            ->orderBy($data['order_by'], $data['order']);

        if (!empty($data['search'])) {
            $data['search'] = esc_sql($data['search']);
            $query->whereRaw("post.post_id = '{$data['search']}' OR post.post_title LIKE '%{$data['search']}%'");
        }

        if (!empty($data['term_slug'])) {
            $term_slug = $data['term_slug'];
            $query->join('term_relation', 'post.post_id', '=', 'term_relation.service_id', 'inner')->join('term', 'term_relation.term_id', '=', 'term.term_id', 'inner');
            $query->where('term.term_name', $term_slug);
        }
        if (is_array($data['term_ids'])) {
            $term_ids = array_map('intval', $data['term_ids']);
            $count = count($term_ids);
            $term_ids = implode(',', $term_ids);
            $query->whereRaw("(SELECT COUNT(1) FROM term_relation WHERE term_id IN ({$term_ids}) AND service_id = post.post_id AND post_type = 'post') = {$count}");

        }
        if (is_array($data['status'])) {
            $status = array_map('trim', $data['status']);
            $query->whereIn('post.status', $status);
        } else {
            $query->whereNotIn('post.status', ['revision']);
        }

        if ($number != -1) {
            $offset = ($data['page'] - 1) * $number;
            $query->limit($number)->offset($offset);
        }

        $results = $query->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];

    }

    public function listOfPosts($data)
    {
        $default = [
            'id' => '',
            'page' => 1,
            'orderby' => 'post_id',
            'order' => 'desc',
            'number' => posts_per_page()
        ];

        $data = wp_parse_args($data, $default);
        $number = $data['number'];

        $query = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS post.*")->where('post.status', 'publish')->orderBy($data['orderby'], $data['order']);

        if (!empty($data['id'])) {
            $query->whereRaw("post.post_id IN ({$data['id']})");
        }

        if ($number != -1) {
            $offset = ($data['page'] - 1) * $number;
            $query->limit($number)->offset($offset);
        }

        $results = $query->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class Comment extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'comment_id';

    public function deleteCommentsByUserID($user_id){
	    DB::table($this->getTable())->where('comment_author', $user_id)->delete();
    }

    public function getCommentsByUserID($user_id){
	    $data = DB::table($this->getTable())->where('comment_author', $user_id)->get();
	    return $data;
    }

    public function getAllComments($data)
    {
        $default = [
            'type' => 'posts',
            'search' => '',
            'page' => 1,
            'status' => '',
            'orderby' => 'comment_id',
            'order' => 'desc',
            'number' => posts_per_page(),
            'author' => ''
        ];

        $data = wp_parse_args($data, $default);

        $number = $data['number'];

        $tableName = $data['type'];
	    $endOfName = substr($tableName, strlen($tableName) - 1, 1);
	    if($endOfName == 's'){
	    	$tableName = substr($tableName, 0, strlen($tableName) - 1);
	    }

	    $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS comments.*, {$tableName}.post_title, {$tableName}.post_slug")->orderBy($data['orderby'], $data['order']);

        if ($number != -1) {
            $offset = ($data['page'] - 1) * $number;
            $sql->limit($number)->offset($offset);
        }

        if (!empty($data['search'])) {
            $data['search'] = esc_sql($data['search']);
            if (is_numeric($data['search'])) {
                $sql->where('comments.comment_id', $data['search']);
            } else {
                $sql->whereRaw("(comments.comment_title LIKE '%{$data['search']}%' OR comments.comment_content LIKE '%{$data['search']}%' OR comments.comment_name LIKE '%{$data['search']}%' OR comments.comment_email LIKE '%{$data['search']}%')");
            }
        }

        if (!empty($data['type'])) {
            $sql->where('comments.post_type', $data['type']);
        }

        if (!empty($data['status'])) {
            $sql->where('comments.status', $data['status']);
        }

	    $sql->join($tableName, 'comments.post_id', '=', "{$tableName}.post_id", 'inner');

        if (!empty($data['author'])) {
            $sql->where('comment_author', $data['author']);
        }

        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function getCommentByPostID($post_id, $data)
    {
        $default = [
            'page' => 1,
            'parent' => 0,
            'type' => 'posts',
            'number' => -1,
        ];
        $data = wp_parse_args($data, $default);

        $number = $data['number'];

        $post = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS comments.*")
            ->where('post_id', $post_id)
            ->where('post_type', $data['type'])
            ->where('parent', $data['parent'])
            ->where('status', 'publish')
            ->orderBy('comment_id', 'DESC');

        if ($number != -1) {
            $page = intval($data['page']);
            $offset = ($page - 1) * $number;
            $post->limit($number)->offset($offset);
        }

        $results = $post->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'count' => $count,
            'results' => $results
        ];
    }

    public function getCommentCountByPostID($post_id, $type = 'post')
    {
        $comment_number = DB::table($this->getTable())->selectRaw("count(*) as comment_number")
            ->where('post_id', $post_id)
            ->where('status', 'publish')
            ->where('post_type', $type)->count();
        return $comment_number;
    }

    public function updateStatus($comment_id, $new_status = '')
    {
        return DB::table($this->getTable())->where('comment_id', $comment_id)->update(['status' => $new_status]);
    }

    public function deleteComment($comment_id)
    {
        return DB::table($this->table)->where('comment_id', $comment_id)->orWhere('parent', $comment_id)->delete();
    }

    public function updatePost($data, $post_id)
    {
        return DB::table($this->getTable())->where('comment_id', $post_id)->update($data);
    }

    public function getById($post_id)
    {

        $post = DB::table($this->table)->where('comment_id', $post_id)->get()->first();

        return (!empty($post) && is_object($post)) ? $post : null;
    }

    public function createPost($data = [])
    {
        return DB::table($this->getTable())->insertGetId($data);
    }
}

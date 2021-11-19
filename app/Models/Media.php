<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Media extends Model
{
    protected $table = 'media';
    protected $primaryKey = 'media_id';

    public function updateAuthor($data, $author)
    {
        return DB::table($this->getTable())->where('author', $author)->update($data);
    }

    public function deleteAttachment($attachment_id)
    {
        return DB::table($this->table)->where('media_id', $attachment_id)->delete();
    }

    public function issetAttachment($attachment_id)
    {
        $result = DB::table($this->table)->where('media_id', $attachment_id)->get(['media_id'])->first();
        return (!empty($result) && is_object($result)) ? $result->media_id : false;
    }

    public function listAttachments($data = [])
    {
        $default = [
            's' => '',
            'number' => -1,
            'page' => 1,
            'not_in' => '',
            'orderby' => 'created_at',
            'order' => 'desc',
        ];

        $data = wp_parse_args($data, $default);

        $sql = DB::table($this->table)->orderBy($data['orderby'], $data['order']);

        if (!is_admin()) {
            $user_id = get_current_user_id();
            $sql->where('author', $user_id);
        }
        $search = esc_sql(trim($data['s']));
        if (!empty($search)) {
            $sql->whereRaw("(media_id = '{$search}') OR (media_title LIKE '%{$search}%')");
        }
        $number = intval($data['number']);

        if ($number !== -1) {
            $offset = ($data['page'] - 1) * $number;
            $sql->limit($number)->offset($offset);
        }

        if(is_array($data['not_in'])){
            $sql->whereNotIn('media_id', $data['not_in']);
        }

        $results = $sql->get();
        return is_object($results) && !$results->isEmpty() ? $results : null;
    }

    public function getById($attachment_id)
    {
        $attachment = DB::table($this->table)->where('media_id', $attachment_id)->get()->first();
        return is_object($attachment) ? $attachment : null;
    }

    public function getByAuthor($user_id)
    {
        $results = DB::table($this->table)->where('author', $user_id)->get();
        return !empty($results) && is_object($results) ? $results : null;
    }

    public function updateMedia($data, $media_id)
    {
        return DB::table($this->getTable())->where('media_id', $media_id)->update($data);
    }

    public function create($data = [])
    {
        return DB::table($this->getTable())->insertGetId($data);
    }
}

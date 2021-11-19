<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    protected $table = 'notification';
    protected $primaryKey = 'ID';

    public function allNotifications($data = []): array
    {
        $default = [
            'page' => 1,
            'user_id' => get_current_user_id(),
            'user_type' => 'user_to',
            'number' => posts_per_page()
        ];

        $data = array_merge($default, $data);
        $number = intval($data['number']);

        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS *");

        if ($data['user_id']) {
            $sql->where($data['user_type'], $data['user_id']);
        }

        $offset = ($data['page'] - 1) * $number;

        $sql->limit($number)->offset($offset);

        $sql->orderBy('ID', 'desc');

        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function countNotificationByUser($user_id, $type = 'to')
    {
        $last_time = (int)get_user_meta($user_id, 'last_check_notification', 0);
        if (!$last_time) {
            $last_time = 0;
        }
        if ($type == 'to') {

            $count = DB::table($this->getTable())->where('user_to', $user_id)->whereRaw("created_at >= {$last_time}")->count();
        } else {

            $count = DB::table($this->getTable())->where('user_from', $user_id)->whereRaw("created_at >= {$last_time}")->count();
        }

        return [
            'total' => $count
        ];
    }

    public function deleteNotification($noti_id)
    {
        return DB::table($this->getTable())->where('ID', $noti_id)->delete();
    }

    public function insertNotification($data = [])
    {
        return DB::table($this->getTable())->insertGetId($data);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class Payout extends Model
{
    protected $table = 'payout';
    protected $primaryKey = 'ID';

    public function getPayout($payout_id)
    {
        $result = DB::table($this->getTable())->where('ID', $payout_id)->get()->first();
        return (!empty($result) && is_object($result)) ? $result : null;
    }

    public function getAllPayout($data = []){
        $default = [
            'search' => '',
            'payout_id' => '',
            'user_id' => '',
            'status' => '',
            'orderby' => 'ID',
            'order' => 'desc',
            'page' => 1,
            'number' => posts_per_page(),
        ];

        $data = wp_parse_args($data, $default);

        $sql = DB::table($this->getTable());
        if(!empty($data['payout_id'])){
            $sql->where('payout_id', $data['payout_id']);
        }
        if(!empty($data['user_id'])){
            $sql->where('user_id', $data['user_id']);
        }
        if(!empty($data['status'])){
            $sql->where('status', $data['status']);
        }
        if (!empty($data['search'])) {
            $data['search'] = esc_sql($data['search']);
            $sql->whereRaw("(payout_id LIKE '%{$data['search']}%' OR user_id LIKE '%{$data['search']}%')");
        }

        $sql->orderBy($data['orderby'], $data['order']);

        $number = $data['number'];
        if ($number != -1) {
            $offset = ($data['page'] - 1) * $number;
            $sql->limit($number)->offset($offset);
        }

        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function updatePayout($payout_id, $data = [])
    {
        return DB::table($this->getTable())->where('ID', $payout_id)->update($data);
    }

    public function insertPayout($data = [])
    {
        return DB::table($this->getTable())->insertGetId($data);
    }

    public function deletePayout($payout_id)
    {
        return DB::table($this->table)->where('ID', $payout_id)->delete();
    }
}

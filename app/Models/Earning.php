<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class Earning extends Model
{
    protected $table = 'earning';
    protected $primaryKey = 'ID';

    public function getEarning($user_id)
    {
        $result = DB::table($this->getTable())->where('user_id', $user_id)->get()->first();
        return (!empty($result) && is_object($result)) ? $result : null;
    }

    public function updateEarning($user_id, $data = [])
    {
        return DB::table($this->getTable())->where('user_id', $user_id)->update($data);
    }

    public function insertEarning($data = [])
    {
        return DB::table($this->getTable())->insertGetId($data);
    }
}

<?php
/**
 * Created by PhpStorm.
 * Date: 10/22/2019
 * Time: 12:37 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Option extends Model
{
    protected $table = 'options';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'option_name', 'option_value'
    ];

    public function createOption($option_id, $option_value)
    {
        $data = [
            'option_name' => $option_id,
            'option_value' => $option_value
        ];
        return DB::table($this->getTable())->insertGetId($data);
    }

    public function updateOption($option_id, $option_value)
    {
        $data = [
            'option_value' => $option_value
        ];
        return DB::table($this->table)->where('option_name', $option_id)->update($data);
    }

    public function deleteOption($option_id)
    {
        return DB::table($this->getTable())->where('option_name', $option_id)->delete();
    }

    public function getOption($option_id)
    {
        return DB::table($this->getTable())->where('option_name', $option_id)->get()->first();
    }

    public function hasOption($option_id)
    {
        return DB::table($this->getTable())->where('option_name', $option_id)->get(['option_id'])->first();
    }
}

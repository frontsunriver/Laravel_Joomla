<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExperienceAvailability extends Model
{
    protected $table = 'experience_availability';
    protected $primaryKey = 'id';

    public function getAvailabilityItems($service_id, $start= null, $end = null)
    {
        $start = esc_sql($start);
        $end = esc_sql($end);
        $sql = DB::table($this->getTable());
        if(!empty($start) && !empty($end)){
            $sql->whereRaw("date between {$start} and {$end}");
        }
        if(!empty($start) && empty($end)){
            $sql->where('date', '>=', $start);
        }

        $sql->where('experience_id', $service_id);

        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;
        return [
            'total' => $count,
            'results' => $results
        ];
    }
    public function getAvailabilityItem($experience_id, $date){
        $result = DB::table($this->getTable())->where('experience_id', $experience_id)->where('date', $date)->get()->first();
       return (is_object($result))? $result: null;
    }
    public function createAvailabilityItem($data){
        return DB::table($this->getTable())->insertGetId($data);
    }
    public function deleteAvailabilityItem($experience_id, $date){
        return DB::table($this->getTable())->where('experience_id', $experience_id)->where('date', $date)->delete();
    }
}

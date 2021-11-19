<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExperienceBooking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'ID';

    public function getExperienceBookingItemInTime($startTime = '', $type = 'date_time')
    {
        $sql = DB::table($this->getTable())->where('service_type', 'experience');
        if ($type == 'date_time') {
            $sql->where('start_time', $startTime);
        } elseif ($type = 'just_date') {
            $sql->where('start_date', $startTime);
        }

        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function countPeopleExperienceBookingInTime($startTime = '', $type = 'date_time')
    {
        $sql = DB::table($this->getTable())->selectRaw('SUM(number_of_guest) as people')->where('service_type', 'experience');
        if ($type == 'date_time') {
            $sql->where('start_time', $startTime);
        } elseif ($type = 'just_date') {
            $sql->where('start_date', $startTime);
        }
        $results = $sql->get()->first();

        return (is_object($results)) ? (int)$results->people : 0;
    }
}

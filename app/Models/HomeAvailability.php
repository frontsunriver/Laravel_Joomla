<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HomeAvailability extends Model
{
    protected $table = 'home_availability';

    public function getAllAvailability($home_id)
    {
        $sql = DB::table($this->getTable())->where('home_id', $home_id);
        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];

    }

    public function getAvailabilityItems($home_id, $start = null, $end= null, $booking_id = null)
    {
        $start = esc_sql($start);
        $end = esc_sql($end);

        $sql = DB::table($this->getTable())->where('home_id', $home_id);
        if(!empty($start) && !empty($end)){
            $sql->whereRaw("((start_date >= {$start} AND end_date <= {$end}) OR (start_date <= {$start} AND end_date >= {$start}) OR (start_date <= {$end} AND end_date >= {$end}))");
        }
        if(!empty($start) && empty($end)){
            $sql->where('start_date', '>=', $start);
        }
        if (!is_null($booking_id)) {
            $sql->where('booking_id', $booking_id);
        }
        $sql->orderBy('start_date', 'ASC');
        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;
        return [
            'total' => $count,
            'results' => $results
        ];
    }
    public function getAvailabilityTimeItems($home_id, $start, $end, $group = true)
    {
        $sql = DB::table($this->getTable());
        if($group){
            $sql->selectRaw('*, sum(total_minutes) AS total, sum(booking_id) as has_booking');
        }
        $sql->whereRaw("(
                (
                    start_date >= {$start}
                    AND end_date <= {$end}
                )
                OR (
                    start_date <= {$start}
                    AND end_date >= {$start}
                )
                OR (
                    start_date <= {$end}
                    AND end_date >= {$end}
                )
            )
            AND home_id = {$home_id}");
        if($group) {
            $sql->groupby('start_date');
        }

        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;
        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function _saveAvailability($home_id, $start, $end, $status)
    {
        $availability = $this->getAvailabilityItems($home_id, $start, $end, 0);
        if ($availability['total']) {
            foreach ($availability['results'] as $key => $item) {
                if ($item->start_time >= $start && $item->end_time <= $end) {
                    $this->deleteAvailability($home_id, $item->start_date, $item->end_date);
                } else {
                    if ($item->start_time < $start) {
                        $this->createAvailability([
                            'home_id' => $home_id,
                            'start_time' => $item->start_time,
                            'start_date' => $item->start_time,
                            'end_time' => strtotime('-1 day', $start),
                            'end_date' => strtotime('-1 day', $start),
                            'total_minutes' => $item->total_minutes
                        ]);
                    }
                    if ($item->end_time > $end) {
                        $this->createAvailability([
                            'home_id' => $home_id,
                            'start_time' => strtotime('+1 day', $end),
                            'start_date' => strtotime('+1 day', $end),
                            'end_time' => $item->end_time,
                            'end_date' => $item->end_time,
                            'total_minutes' => $item->total_minutes
                        ]);
                    }
                    $this->deleteAvailability($item->home_id, $item->start_date, $item->end_date);
                }
            }
        }
        if($status == 'off'){
            $this->createAvailability([
                'home_id' => $home_id,
                'start_time' => $start,
                'start_date' => $start,
                'end_time' => $end,
                'end_date' => $end,
                'total_minutes' => 1440
            ]);
        }
    }

    public function getItem($home_id, $start_date, $end_date, $type = 'date')
    {
        if($type == 'date'){
            return DB::table($this->getTable())->where('home_id', $home_id)->where('start_date', $start_date)->where('end_date', $end_date)->get()->first();
        }else{
            return DB::table($this->getTable())->where('home_id', $home_id)->where('start_time', $start_date)->where('end_time', $end_date)->get()->first();
        }
    }

    public function getItemByBooking($booking_id)
    {
        return DB::table($this->getTable())->where('booking_id', $booking_id)->get()->first();
    }

    public function createAvailability($data = [])
    {
        return DB::table($this->getTable())->insertGetId($data);
    }

    public function deleteAvailability($home_id, $start_date, $end_date)
    {
        return DB::table($this->table)->where('home_id', $home_id)->where('start_date', $start_date)->where('end_date', $end_date)->delete();
    }

    public function deleteAvailabilityByBooking($booking_id)
    {
        return DB::table($this->table)->where('booking_id', $booking_id)->delete();
    }

}

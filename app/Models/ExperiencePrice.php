<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class ExperiencePrice extends Model
{
    protected $table = 'experience_price';

    public function getAllPrices($experience_id)
    {
        $sql = DB::table($this->getTable())->where('experience_id', $experience_id)->orderBy('ID');
        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function getPriceItems($experience_id, $start, $end, $by = 'date_time')
    {
        $start = esc_sql($start);
        $end = esc_sql($end);
        if ($by == 'date_time') {
            $sql = DB::table($this->getTable())->whereRaw("((experience_price.start_time >= {$start} AND experience_price.end_time <= {$end}) OR (experience_price.start_time <= {$start} AND experience_price.end_time >= {$start}) OR (experience_price.start_time <= {$end} AND experience_price.end_time >= {$end})) AND experience_price.experience_id = {$experience_id}");
        } else {
            $sql = DB::table($this->getTable())->whereRaw("((experience_price.start_date >= {$start} AND experience_price.end_date <= {$end}) OR (experience_price.start_date <= {$start} AND experience_price.end_date >= {$start}) OR (experience_price.start_date <= {$end} AND experience_price.end_date >= {$end})) AND experience_price.experience_id = {$experience_id}");
        }
        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;
        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function getPriceItem($experience_id, $start, $by = 'date_time')
    {
        $start = esc_sql($start);
        if ($by == 'date_time') {
            $sql = DB::table($this->getTable())->whereRaw("experience_price.start_time = {$start} AND experience_price.end_time = {$start} AND experience_price.experience_id = {$experience_id}");
        } else {
            $sql = DB::table($this->getTable())->whereRaw("experience_price.start_date = {$start} AND experience_price.end_date = {$start} AND experience_price.experience_id = {$experience_id}");
        }
        $results = $sql->get()->first();
        return (is_object($results)) ? $results : null;
    }

    public function _savePrice($experience_id, $start, $end, $adult_price, $child_price, $infant_price, $max_people)
    {
        $availability = $this->getPriceItems($experience_id, $start, $end);
        if ($availability['total']) {
            foreach ($availability['results'] as $key => $item) {
                if ($item->start_time >= $start && $item->end_time <= $end) {
                    $this->deletePrice($item->ID);
                } else {
                    if ($item->start_time < $start) {
                        $this->createPrice([
                            'experience_id' => $experience_id,
                            'start_time' => $item->start_time,
                            'start_date' => $item->start_date,
                            'end_time' => strtotime('-1 day', $start),
                            'end_date' => strtotime('-1 day', strtotime(date('Y-m-d', $start))),
                            'adult_price' => $item->adult_price,
                            'child_price' => $item->child_price,
                            'infant_price' => $item->infant_price,
                            'max_people' => $max_people
                        ]);
                    }
                    if ($item->end_time > $end) {
                        $this->createPrice([
                            'experience_id' => $experience_id,
                            'start_time' => strtotime('+1 day', $end),
                            'start_date' => strtotime('+1 day', strtotime(date('Y-m-d', $end))),
                            'end_time' => $item->end_time,
                            'end_date' => $item->end_date,
                            'adult_price' => $item->adult_price,
                            'child_price' => $item->child_price,
                            'infant_price' => $item->infant_price,
                            'max_people' => $max_people
                        ]);
                    }
                    $this->deletePrice($item->ID);
                }
            }
        }
        return $this->createPrice([
            'experience_id' => $experience_id,
            'start_time' => $start,
            'start_date' => strtotime(date('Y-m-d', $start)),
            'end_time' => $end,
            'end_date' => strtotime(date('Y-m-d', $end)),
            'adult_price' => $adult_price,
            'child_price' => $child_price,
            'infant_price' => $infant_price,
            'max_people' => $max_people
        ]);
    }


    public function getByID($price_id)
    {
        return DB::table($this->getTable())->where('ID', $price_id)->get()->first();
    }

    public function updatePrice($data, $price_id)
    {
        return DB::table($this->getTable())->where('ID', $price_id)->update($data);
    }

    public function createPrice($data = [])
    {
        return DB::table($this->getTable())->insertGetId($data);
    }

    public function deletePrice($price_id)
    {
        return DB::table($this->table)->where('ID', $price_id)->delete();
    }
}

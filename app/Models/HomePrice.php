<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class HomePrice extends Model
{
    protected $table = 'home_price';

    public function getAllPrices($home_id)
    {
        $sql = DB::table($this->getTable())->where('home_id', $home_id)->orderBy('ID');
        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function getPriceItems($home_id, $start, $end, $status = 'all')
    {
        $start = esc_sql($start);
        $end = esc_sql($end);
        $sql = DB::table($this->getTable())->whereRaw("((home_price.start_time >= {$start} AND home_price.end_time <= {$end}) OR (home_price.start_time <= {$start} AND home_price.end_time >= {$start}) OR (home_price.start_time <= {$end} AND home_price.end_time >= {$end})) AND home_price.home_id = {$home_id}");
        if ($status != 'all') {
            $sql->where('available', $status);
        }
        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;
        return [
            'total' => $count,
            'results' => $results
        ];
    }
    public function _savePrice( $home_id, $start, $end, $price, $status )
    {
        $availability = $this->getPriceItems( $home_id, $start, $end );
        if ( $availability['total'] ) {
            foreach ( $availability['results'] as $key => $item ) {
                if ( $item->start_time >= $start && $item->end_time <= $end ) {
                    $this->deletePrice( $item->ID );
                } else {
                    if ( $item->start_time < $start ) {
                        $this->createPrice( [
                            'home_id' => $home_id,
                            'start_time' => $item->start_time,
                            'end_time' => strtotime( '-1 day', $start ),
                            'price' => $item->price,
                            'available' => $item->available
                        ] );
                    }
                    if ( $item->end_time > $end ) {
                        $this->createPrice( [
                            'home_id' => $home_id,
                            'start_time' => strtotime( '+1 day', $end ),
                            'end_time' => $item->end_time,
                            'price' => $item->price,
                            'available' => $item->available
                        ] );
                    }
                    $this->deletePrice( $item->ID );
                }
            }
        }
        return $this->createPrice( [
            'home_id' => $home_id,
            'start_time' => $start,
            'end_time' => $end,
            'price' => $price,
            'available' => $status
        ] );
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

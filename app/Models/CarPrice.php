<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class CarPrice extends Model
{
    protected $table = 'car_price';

    public function getAllPrices($experience_id)
    {
        $sql = DB::table($this->getTable())->where('car_id', $experience_id)->orderBy('ID');
        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function getPriceItems($car_id, $start, $end, $status = 'all')
    {
        $start = esc_sql($start);
        $end = esc_sql($end);
        $sql = DB::table($this->getTable())->whereRaw("((car_price.start_date >= {$start} AND car_price.end_date <= {$end}) OR (car_price.start_date <= {$start} AND car_price.end_date >= {$start}) OR (car_price.start_date <= {$end} AND car_price.end_date >= {$end})) AND car_price.car_id = {$car_id}");
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
    public function _savePrice( $car_id, $start, $end, $price, $status )
    {
        $availability = $this->getPriceItems( $car_id, $start, $end );
        if ( $availability['total'] ) {
            foreach ( $availability['results'] as $key => $item ) {
                if ( $item->start_date >= $start && $item->end_date <= $end ) {
                    $this->deletePrice( $item->ID );
                } else {
                    if ( $item->start_date < $start ) {
                        $this->createPrice( [
                            'car_id' => $car_id,
                            'start_date' => $item->start_date,
                            'end_date' => strtotime( '-1 day', $start ),
                            'price' => $item->price,
                            'available' => $item->available
                        ] );
                    }
                    if ( $item->end_date > $end ) {
                        $this->createPrice( [
                            'car_id' => $car_id,
                            'start_date' => strtotime( '+1 day', $end ),
                            'end_date' => $item->end_date,
                            'price' => $item->price,
                            'available' => $item->available
                        ] );
                    }
                    $this->deletePrice( $item->ID );
                }
            }
        }
        return $this->createPrice( [
            'car_id' => $car_id,
            'start_date' => $start,
            'end_date' => $end,
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

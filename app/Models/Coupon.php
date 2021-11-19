<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;
use function Symfony\Component\VarDumper\Dumper\esc;

class Coupon extends Model
{
    protected $table = 'coupon';
    protected $primaryKey = 'coupon_id';

    public function getAllCoupons($data = [])
    {
        $default = [
            'search' => '',
            'page' => 1,
            'orderby' => 'coupon_id',
            'order' => 'desc',
            'status' => '',
            'author' => '',
            'number' => posts_per_page()
        ];
        $data = wp_parse_args($data, $default);
        $number = $data['number'];
        $offset = ($data['page'] - 1) * $number;

        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS coupon.*")
            ->orderBy($data['orderby'], $data['order'])->limit($number)->offset($offset);

        if (!empty($data['search'])) {
            $data['search'] = esc_sql($data['search']);
            if (is_numeric($data['search'])) {
                $sql->where('coupon.coupon_id', $data['search']);
            } else {
                $sql->whereRaw("coupon.coupon_code LIKE '%{$data['search']}%'");
            }
        }
        if (!empty($data['status'])) {
            $sql->where('coupon.status', $data['status']);
        }

        if (!empty($data['author'])) {
            $sql->where('coupon.author', $data['author']);
        }
        $results = $sql->get();

        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];

    }

    public function getCouponItem($couponCode = '', $by = 'code')
    {
        $result = DB::table($this->getTable())->where('coupon.coupon_code', $couponCode)->get()->first();
        return (!empty($result) && is_object($result)) ? $result : null;
    }


    public function getById($coupon_id)
    {
        return DB::table($this->table)->where('coupon_id', $coupon_id)->get()->first();
    }

    public function getByCode($coupon_code)
    {
        return DB::table($this->table)->where('coupon_code', $coupon_code)->get()->first();
    }

    public function updateCoupon($data, $coupon_id)
    {
        return DB::table($this->getTable())->where('coupon_id', $coupon_id)->update($data);
    }

    public function createCoupon($data = [])
    {
        return DB::table($this->getTable())->insertGetId($data);
    }

    public function deleteCoupon($coupon_id)
    {
        return DB::table($this->table)->where('coupon_id', $coupon_id)->delete();
    }
}

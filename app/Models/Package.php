<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class Package extends Model
{
    protected $table = 'package';
    protected $primaryKey = 'package_id';

    public function getItemByPartnerID($user_id){
	    $res = DB::table($this->getTable())->where('partner_id', $user_id)->get();
	    return $res;
    }

    public function getTotaPackge(){
	    $package_number = DB::table($this->getTable())->selectRaw("count(*) as package_number")->count();
	    return $package_number;
    }

    public function getAllPackages($data = [])
    {
        $default = [
            'search' => '',
            'page' => 1,
            'number' => posts_per_page()
        ];
        $data = wp_parse_args($data, $default);
        $number =$data['number'];
        $offset = ($data['page'] - 1) * $number;

        $sql = DB::table($this->getTable())->selectRaw("SQL_CALC_FOUND_ROWS package.*")
            ->orderBy('package_id', 'ASC')->limit($number)->offset($offset);

        if (!empty($data['search'])) {
            $data['search'] = esc_sql($data['search']);
            if (is_numeric($data['search'])) {
                $sql->where('package.package_id', $data['search']);
            } else {
                $sql->whereRaw("package.package_name LIKE '%{$data['search']}%'");
            }
        }
        $results = $sql->get();
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        return [
            'total' => $count,
            'results' => $results
        ];
    }

    public function getById($package_id)
    {
        return DB::table($this->table)->where('package_id', $package_id)->get()->first();
    }

    public function updatePackage($data, $package_id)
    {
        return DB::table($this->getTable())->where('package_id', $package_id)->update($data);
    }

    public function createPackage($data = [])
    {
        return DB::table($this->getTable())->insertGetId($data);
    }

    public function deletePackage($package_id)
    {
        return DB::table($this->table)->where('package_id', $package_id)->delete();
    }
}

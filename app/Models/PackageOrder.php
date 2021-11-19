<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sentinel;

class PackageOrder extends Model
{
    protected $table = 'package_order';
    protected $primaryKey = 'pkorder_id';

    public function getItemByPartnerID($user_id){
	    $res = DB::table($this->getTable())->where('partner_id', $user_id)->get();
	    $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;
	    return [
	    	'total' => $count,
		    'results' => $res

	    ];

	    return $res;
    }
}

<?php

namespace App\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtherController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
    }

    public function getCountries(){
    	return $this->sendJson([
    		'status' => 1,
		    'message' => __('Success'),
		    'data' => json_decode(file_get_contents(public_path('vendor/countries/countries.json')), true)
	    ]);
    }
}

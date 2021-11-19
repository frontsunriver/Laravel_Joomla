<?php

namespace App\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Sentinel;

class APIController extends Controller
{
    public function token(Request $request)
    {
        $rules = [
            'token' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendJson([
                'status' => 0,
                'message' => $validator->errors()
            ]);
        }

        $data = parse_request($request, array_keys($rules));

        $token_expired = api_token_valid($data['token']);

        return $this->sendJson([
            'status' => 1,
            'token' => $data['token'],
            'valid' => $token_expired
        ]);
    }

    protected function parseRequestParams($request, $parse){
    	$data = [];
    	if(!empty($request)){
    		foreach ($request as $k => $v){
				if(isset($parse[$k])){
					$data[$parse[$k]] = $v;
				}else{
					$data[$k] = $v;
				}
		    }
	    }
	    return $data;
    }

}

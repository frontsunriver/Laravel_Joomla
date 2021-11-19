<?php

namespace App\Controllers\Api;

use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
	public function index(){
		$enabled = is_multi_language();
		if($enabled){
			$languages = get_languages(true);
			if(!$languages->isEmpty()){
                return $this->sendJson([
					'status' => true,
                    'message' => __('Success'),
					'data' => $languages->toArray()
				]);
			}
		}
        return $this->sendJson([
            'status' => false,
            'message' => __('Failed'),
            'data' => []
        ]);
	}
}

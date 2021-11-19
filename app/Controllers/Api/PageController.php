<?php

namespace App\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
	public function __construct() {
		$this->model = new Page();
	}

    public function show($id, Request $request)
    {
        $lang = $request->get('lang', get_current_language());
        $data = $this->model->getById($id);

        if($data){
            $data->post_title = get_translate($data->post_title, $lang);
            $data->post_content = get_translate($data->post_content, $lang);
	        $data->thumbnail_url = get_attachment_url($data->thumbnail_id);
	        $data->created_at = date(hh_date_format(), $data->created_at);
	        return $this->sendJson([
		        'status' => true,
		        'message' => __('Success'),
		        'data' => $data
	        ]);
        }
	    return $this->sendJson([
		    'status' => false,
		    'message' => __('Can not get data')
	    ]);
    }
}

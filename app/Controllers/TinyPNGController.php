<?php
/**
 * Created by PhpStorm.
 * Date: 1/7/2020
 * Time: 4:00 PM
 */

namespace App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sentinel;

class TinyPNGController extends Controller
{

    public function _tinyPngCompressPost(Request $request)
    {
        $tinypng_enable = $request->get('tinypng_enable', 'off');
        $tinypng_api_key = $request->get('tinypng_api_key', '');
        update_opt('tinypng_enable', $tinypng_enable);
        update_opt('tinypng_api_key', $tinypng_api_key);

        return $this->sendJson([
            'status' => 1,
            'title' => __('System Alert'),
            'message' => __('Saved Successfully'),
            'reload' => true
        ]);
    }

    public function _tinyPngCompress(Request $request)
    {
        return view('dashboard.screens.'.$this->getFolder().'.tinypng-compress');
    }

    private function _init()
    {
        \Tinify\setKey("YOUR_API_KEY");
    }
}

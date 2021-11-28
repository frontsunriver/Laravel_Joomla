<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Sentinel;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
    }

    public function getFolder()
    {
        $folder = 'customer';
        if (Sentinel::inRole('administrator')) {
            $folder = 'administrator';
        } elseif (Sentinel::inRole('partner')) {
            $folder = 'partner';
        } elseif (Sentinel::inRole('superadmin')) {
            $folder = 'superadmin';
        }

        return $folder;
    }

    public function sendJson($data, $withDie = false)
    {
        if ($withDie) {
            echo json_encode($data);
            die;
        } else {
            return response()->json($data);
        }
    }
}

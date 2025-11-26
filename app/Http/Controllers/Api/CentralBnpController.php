<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CentralBnp;


class CentralBnpController extends Controller
{

    public function index()
    {
        $centralBnp = CentralBnp::latest()->get();
        return response()->json($centralBnp, 200);
    }
    public function singleCentralBnp($slug)
    {
        $centralBnp = CentralBnp::where('slug', $slug)->first();

        if (!$centralBnp) {
            return response()->json([
                'message' => 'Central BNP not found'
            ], 404);
        }

        return response()->json($centralBnp, 200);
    }
}


<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Events;


class EventsController extends Controller
{

    public function index()
    {
        $events = Events::latest()->get();
        return response()->json($events, 200);
    }
    public function singleNews($slug)
    {
        $event = Events::where('slug', $slug)->first();

        if (!$event) {
            return response()->json([
                'message' => 'events not found'
            ], 404);
        }

        return response()->json($event, 200);
    }
}

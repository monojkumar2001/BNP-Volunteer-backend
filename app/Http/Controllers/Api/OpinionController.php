<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Opinion;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
    /**
     * Store a newly created opinion in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'category' => 'required|string|max:255',
            'location' => 'nullable|string',
            'message' => 'required|string',
        ]);

        // If category is 3, location is required
        if ($validated['category'] == '3' && empty($validated['location'])) {
            return response()->json([
                'message' => 'Location is required for this category.',
                'errors' => ['location' => ['Location field is required for incident reports.']]
            ], 422);
        }

        $opinion = Opinion::create([
            'name' => $validated['name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'category' => $validated['category'],
            'location' => $validated['location'] ?? null,
            'message' => $validated['message'],
            'status' => 0,
        ]);

        return response()->json([
            'message' => 'Opinion submitted successfully!',
            'data' => $opinion
        ], 201);
    }

    /**
     * Display a listing of opinions (Admin use)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $opinions = Opinion::latest()->get();
        return response()->json($opinions, 200);
    }
}


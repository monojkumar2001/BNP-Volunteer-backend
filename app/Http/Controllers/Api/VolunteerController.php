<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Volunteer;

class VolunteerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $volunteers = Volunteer::latest()->get();
        return response()->json($volunteers, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:20',
            'division'  => 'nullable|string|max:100',
            'district'  => 'nullable|string|max:100',
            'upazilla'  => 'nullable|string|max:100',
            'union'     => 'nullable|string|max:100',
            'ward'      => 'nullable|string|max:100',
            'address'   => 'nullable|string',
        ]);

        $volunteer = Volunteer::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'] ?? null,
            'phone'     => $validated['phone'] ?? null,
            'division'  => $validated['division'] ?? null,
            'district'  => $validated['district'] ?? null,
            'upazilla'  => $validated['upazilla'] ?? null,
            'union'     => $validated['union'] ?? null,
            'ward'      => $validated['ward'] ?? null,
            'address'   => $validated['address'] ?? null,
            'status'    => 'pending',
        ]);

        return response()->json([
            'message' => 'Volunteer registered successfully!',
            'data' => $volunteer,
        ], 201);
    }
}

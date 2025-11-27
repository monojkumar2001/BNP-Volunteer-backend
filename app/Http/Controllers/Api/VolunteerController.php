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
     * Normalize phone number to standard format (01792892191)
     */
    private function normalizePhone($phone)
    {
        // Clean phone number (remove spaces, dashes, etc.)
        $cleaned = preg_replace('/[\s\-\(\)]/', '', $phone);
        
        // Normalize: +8801792892191, 8801792892191, 01792892191 -> 01792892191
        // Extract last 10 digits (1 + 9 digits) and prepend with 0
        if (preg_match('/(\d{10})$/', $cleaned, $matches)) {
            return '0' . $matches[1];
        }
        
        return $cleaned;
    }

    /**
     * Check if phone number already exists
     */
    public function checkPhone(Request $request)
    {
        $phone = $request->query('phone');
        
        if (!$phone) {
            return response()->json([
                'exists' => false,
                'message' => 'Phone number is required'
            ], 400);
        }

        // Normalize phone number
        $normalizedPhone = $this->normalizePhone($phone);
        
        // Check if phone exists (check all possible formats)
        $exists = Volunteer::where(function($query) use ($normalizedPhone) {
            $query->where('phone', $normalizedPhone)
                  ->orWhere('phone', '+880' . substr($normalizedPhone, 1))
                  ->orWhere('phone', '880' . substr($normalizedPhone, 1))
                  ->orWhere('phone', substr($normalizedPhone, 1));
        })->exists();

        return response()->json([
            'exists' => $exists,
            'phone' => $normalizedPhone
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'required|string|max:20',
            'division'  => 'nullable|string|max:100',
            'district'  => 'nullable|string|max:100',
            'upazilla'  => 'nullable|string|max:100',
            'union'     => 'nullable|string|max:100',
            'ward'      => 'nullable|string|max:100',
            'address'   => 'nullable|string',
        ]);

        // Normalize phone number
        $normalizedPhone = $this->normalizePhone($validated['phone']);

        // Check for duplicate phone number (check all possible formats)
        $duplicate = Volunteer::where(function($query) use ($normalizedPhone) {
            $query->where('phone', $normalizedPhone)
                  ->orWhere('phone', '+880' . substr($normalizedPhone, 1))
                  ->orWhere('phone', '880' . substr($normalizedPhone, 1))
                  ->orWhere('phone', substr($normalizedPhone, 1));
        })->exists();

        if ($duplicate) {
            return response()->json([
                'message' => 'This phone number has already been registered.',
                'errors' => ['phone' => ['This phone number has already been used.']]
            ], 422);
        }

        $volunteer = Volunteer::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'] ?? null,
            'phone'     => $normalizedPhone,
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

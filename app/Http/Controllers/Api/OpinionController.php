<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Opinion;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
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
        $exists = Opinion::where(function($query) use ($normalizedPhone) {
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

        // Clean and normalize phone number if provided
        if (!empty($validated['phone'])) {
            $normalizedPhone = $this->normalizePhone($validated['phone']);

            // Check for duplicate phone number (check all possible formats)
            $duplicate = Opinion::where(function($query) use ($normalizedPhone) {
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

            // Save in normalized format
            $validated['phone'] = $normalizedPhone;
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


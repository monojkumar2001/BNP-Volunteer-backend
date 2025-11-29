<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
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
        $exists = ContactUs::where(function($query) use ($normalizedPhone) {
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        // Clean and normalize phone number if provided
        if (!empty($validated['phone'])) {
            $normalizedPhone = $this->normalizePhone($validated['phone']);
            // Save in normalized format
            $validated['phone'] = $normalizedPhone;
        }

        ContactUs::create([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'] ?? null,
            'status' => 0,
        ]);

        return response()->json(['message' => 'Contact message submitted successfully!'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function show(ContactUs $contactUs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactUs $contactUs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactUs $contactUs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactUs $contactUs)
    {
        //
    }
}

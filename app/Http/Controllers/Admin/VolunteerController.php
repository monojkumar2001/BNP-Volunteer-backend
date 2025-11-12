<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;

class VolunteerController extends Controller
{
    public function index()
    {
        $volunteers = Volunteer::latest()->get();
        return view('admin.volunteer.index', compact('volunteers'));
    }

    public function create()
    {
        return view('admin.volunteer.create');
    }

    public function show($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        return view('admin.volunteer.show', compact('volunteer'));
    }

    public function edit($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        return view('admin.volunteer.edit', compact('volunteer'));
    }

    public function update($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->update([
            'status' => request('status'),
        ]);

        return redirect()->route('admin.volunteer.index')->with('success', 'Volunteer status updated successfully!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Opinion;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
    /**
     * Display a listing of opinions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $opinions = Opinion::latest()->get();
        return view('admin.opinion.index', compact('opinions'));
    }

    /**
     * Display the specified opinion.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $opinion = Opinion::findOrFail($id);
        
        // Update status to read (1) when viewed
        if ($opinion->status == 0) {
            $opinion->update(['status' => 1]);
        }
        
        return view('admin.opinion.show', compact('opinion'));
    }

    /**
     * Update opinion status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $opinion = Opinion::findOrFail($id);
        
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $opinion->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.opinion.index')->with('success', 'Opinion status updated successfully!');
    }

    /**
     * Remove the specified opinion from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $opinion = Opinion::findOrFail($id);
        $opinion->delete();

        return redirect()->route('admin.opinion.index')->with('success', 'Opinion deleted successfully!');
    }
}


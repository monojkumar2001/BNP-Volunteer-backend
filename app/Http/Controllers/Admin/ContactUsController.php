<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{

    public function index()
    {
        $contacts = ContactUs::latest()->get();
        return view('admin.contact_us.index', compact('contacts'));
    }


    public function show($id)
    {
        $contactUs = ContactUs::findOrFail($id);
        return view('admin.contact_us.show', compact('contactUs'));
    }


    public function edit(ContactUs $contactUs) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contactUs = ContactUs::findOrFail($id);
        $contactUs->update([
            'status' => request('status'),
        ]);

        return redirect()->route('admin.contact_us.index')->with('success', 'Contact status updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contactUs = ContactUs::findOrFail($id);
        $contactUs->delete();

        return redirect()->route('admin.contact_us.index')->with('success', 'Contact deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Opinion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

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

    /**
     * Download single opinion as PDF
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf($id)
    {
        $opinion = Opinion::findOrFail($id);
        $pdf = Pdf::loadView('admin.opinion.pdf-single', compact('opinion'));
        return $pdf->download('opinion-' . $opinion->id . '.pdf');
    }

    /**
     * Download selected opinions as PDF
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function downloadSelectedPdf(Request $request)
    {
        // Handle JSON string input from JavaScript
        $idsJson = $request->input('ids');
        $ids = is_string($idsJson) ? json_decode($idsJson, true) : $idsJson;
        
        if (!$ids || !is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'Please select at least one opinion to download!');
        }

        $request->merge(['ids' => $ids]);
        
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:opinions,id',
        ]);

        $opinions = Opinion::whereIn('id', $ids)->latest()->get();
        
        if ($opinions->isEmpty()) {
            return redirect()->back()->with('error', 'No opinions found!');
        }

        $pdf = Pdf::loadView('admin.opinion.pdf-multiple', compact('opinions'));
        return $pdf->download('opinions-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export all opinions to Excel (CSV format)
     *
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
        $opinions = Opinion::latest()->get();

        $filename = 'opinions-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // Category mapping
        $categories = [
            '1' => 'পরামর্শ/অভিমত (Advice/Opinion)',
            '2' => 'অভিযোগ (Complaint)',
            '3' => 'চাঁদাবাজি/সংঘর্ষ রিপোর্ট (Extortion/Conflict Report)',
            '4' => 'অন্যান্য যোগাযোগ (Other Contact)'
        ];

        $callback = function() use ($opinions, $categories) {
            // Add BOM for UTF-8 to support Bengali characters in Excel
            echo "\xEF\xBB\xBF";
            
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID',
                'Name',
                'Phone',
                'Category',
                'Location',
                'Message',
                'Status',
                'Submitted Date'
            ]);

            foreach ($opinions as $opinion) {
                fputcsv($file, [
                    $opinion->id,
                    $opinion->name ?? 'N/A',
                    $opinion->phone ?? 'N/A',
                    $categories[$opinion->category] ?? $opinion->category,
                    $opinion->location ?? 'N/A',
                    $opinion->message ?? 'N/A',
                    $opinion->status == 0 ? 'Unread' : 'Read',
                    \Carbon\Carbon::parse($opinion->created_at)->format('d-M-Y H:i')
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}


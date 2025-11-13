<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;

class GalleryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $galleryCategories = GalleryCategory::latest()->get();
        return view('admin.galleryCategory.index', compact('galleryCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.galleryCategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:gallery_categories,slug',
            'status' => 'nullable|in:0,1',
        ]);

        // ensure slug: generate from name_en if not provided and ensure unique
        $slug = $validated['slug'] ?? \Str::slug($validated['name_en']);
        $count = GalleryCategory::where('slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        GalleryCategory::create([
            'name_en' => $validated['name_en'],
            'name_bn' => $validated['name_bn'] ?? null,
            'slug' => $slug,
            'status' => $validated['status'] ?? 1,
        ]);

        return redirect()->route('admin.galleryCategory.index')->with('success', 'Gallery Category created successfully!');
    }


    public function show(GalleryCategory $galleryCategory)
    {
        //
    }

    public function edit($id)
    {
        $galleryCategory = GalleryCategory::findOrFail($id);
        return view('admin.galleryCategory.edit', compact('galleryCategory'));
    }


    public function update(Request $request,  $id)
    {
        $galleryCategory = GalleryCategory::findOrFail($id);

        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:gallery_categories,slug,' . $galleryCategory->id,
            'status' => 'nullable|in:0,1',
        ]);

        // ensure slug: generate from name_en if not provided and ensure unique
        $slug = $validated['slug'] ?? \Str::slug($validated['name_en']);
        $count = GalleryCategory::where('slug', $slug)->where('id', '!=', $galleryCategory->id)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $galleryCategory->update([
            'name_en' => $validated['name_en'],
            'name_bn' => $validated['name_bn'] ?? null,
            'slug' => $slug,
            'status' => $validated['status'] ?? 1,
        ]);

        return redirect()->route('admin.galleryCategory.index')->with('success', 'Gallery Category updated successfully!');
    }


    public function destroy($id)
    {
        $galleryCategory = GalleryCategory::findOrFail($id);
        $galleryCategory->delete();
        return redirect()->route('admin.galleryCategory.index')->with('success', 'Gallery Category deleted successfully!');
    }
}

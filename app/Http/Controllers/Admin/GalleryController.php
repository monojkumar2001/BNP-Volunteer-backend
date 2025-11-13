<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;

class GalleryController extends Controller
{

    public function index()
    {
        $galleries = Gallery::latest()->get();
        return view('admin.gallery.index', compact('galleries'));
    }


    public function create()
    {
        $galleryCategories = GalleryCategory::where('status', 1)->get();
        return view('admin.gallery.create', compact('galleryCategories'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'image' => 'nullable|image',
            'status' => 'nullable|in:0,1',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->handleImageUpload($request->file('image'));
        }

        Gallery::create([
            'gallery_category_id' => $validated['gallery_category_id'],
            'image' => $imagePath,
            'status' => $validated['status'] ?? 1,
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item created successfully!');
    }


    public function show(Gallery $gallery)
    {
        //
    }


    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);
        $galleryCategories = GalleryCategory::where('status', 1)->get();
        return view('admin.gallery.edit', compact('gallery', 'galleryCategories'));
    }


    public function update(Request $request,  $id)
    {
        $gallery = Gallery::findOrFail($id);

        $validated = $request->validate([
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'image' => 'nullable|image',
            'status' => 'nullable|in:0,1',
        ]);

        $imagePath = $gallery->image;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($gallery->image && File::exists(public_path($gallery->image))) {
                File::delete(public_path($gallery->image));
            }

            // Upload new image (WebP conversion handled inside handleImageUpload)
            $imagePath = $this->handleImageUpload($request->file('image'));
        }
        $gallery->update([
            'gallery_category_id' => $validated['gallery_category_id'],
            'image' => $imagePath,
            'status' => $validated['status'] ?? 1,
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item updated successfully!');
    }


    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        // remove image
        if ($gallery->image && file_exists(public_path($gallery->image))) {
            @unlink(public_path($gallery->image));
        }
        $gallery->delete();
        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item deleted successfully!');
    }
    protected function handleImageUpload($image)
    {
        $uploadDir = public_path('uploads/gallery');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // create unique base name
        $baseName = time() . '_' . uniqid();
        $webpName = $baseName . '.webp';
        $webpPath = $uploadDir . DIRECTORY_SEPARATOR . $webpName;

        // try to convert using GD
        try {
            $contents = file_get_contents($image->getRealPath());
            if ($contents === false) {
                throw new \Exception('Could not read uploaded file');
            }

            $img = @imagecreatefromstring($contents);
            if ($img !== false && function_exists('imagewebp')) {
                // Ensure truecolor for palette images (GIF/PNG indexed)
                try {
                    $ext = strtolower($image->getClientOriginalExtension() ?? '');

                    // If image is not truecolor, convert
                    if (!imageistruecolor($img)) {
                        if (function_exists('imagepalettetotruecolor')) {
                            @imagepalettetotruecolor($img);
                        } else {
                            $w = imagesx($img);
                            $h = imagesy($img);
                            $true = imagecreatetruecolor($w, $h);

                            // preserve transparency for PNG/GIF
                            if (in_array($ext, ['png', 'gif'])) {
                                imagealphablending($true, false);
                                imagesavealpha($true, true);
                                $transparent = imagecolorallocatealpha($true, 0, 0, 0, 127);
                                imagefilledrectangle($true, 0, 0, $w, $h, $transparent);
                            }

                            imagecopyresampled($true, $img, 0, 0, 0, 0, $w, $h, $w, $h);
                            imagedestroy($img);
                            $img = $true;
                        }
                    } else {
                        // image is truecolor — for PNG make sure alpha is preserved
                        if (in_array($ext, ['png'])) {
                            imagealphablending($img, false);
                            imagesavealpha($img, true);
                        }
                    }

                    // quality 80
                    $result = @imagewebp($img, $webpPath, 80);
                    imagedestroy($img);
                    if ($result) {
                        return 'uploads/gallery/' . $webpName;
                    }
                } catch (\Throwable $e) {
                    // fallthrough to fallback below
                }
            }
        } catch (\Throwable $e) {
            // fallback to move original file
        }

        // fallback: move original file without conversion
        $origExt = $image->getClientOriginalExtension();
        $origName = $baseName . '.' . $origExt;
        $image->move($uploadDir, $origName);
        return 'uploads/gallery/' . $origName;
    }
}

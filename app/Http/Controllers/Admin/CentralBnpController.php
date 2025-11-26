<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CentralBnp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class CentralBnpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $centralBnp = CentralBnp::latest()->get();
        return view('admin.central_bnp.index', compact('centralBnp'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.central_bnp.create');
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
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:central_bnp,slug',
            'short_description_en' => 'nullable|string',
            'short_description_bn' => 'nullable|string',
            'content_en' => 'nullable|string',
            'content_bn' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'nullable|in:0,1',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->handleImageUpload($request->file('image'));
        }

        // ensure slug: generate from title_en if not provided and ensure unique
        $slug = $validated['slug'] ?? Str::slug($validated['title_en']);
        $slug = $this->makeUniqueSlug($slug);

        // Decode any HTML entities to avoid storing escaped tags
        if (!empty($validated['content_en'])) {
            $validated['content_en'] = html_entity_decode($validated['content_en'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        if (!empty($validated['content_bn'])) {
            $validated['content_bn'] = html_entity_decode($validated['content_bn'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        $centralBnp = CentralBnp::create([
            'title_en' => $validated['title_en'],
            'title_bn' => $validated['title_bn'] ?? null,
            'slug' => $slug,
            'short_description_en' => $validated['short_description_en'] ?? null,
            'short_description_bn' => $validated['short_description_bn'] ?? null,
            'content_en' => $validated['content_en'] ?? null,
            'content_bn' => $validated['content_bn'] ?? null,
            'image' => $imagePath,
            'status' => $validated['status'] ?? 1,
        ]);

        return redirect()->route('admin.central_bnp.index')->with('success', 'Central BNP created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CentralBnp  $centralBnp
     * @return \Illuminate\Http\Response
     */
    public function show(CentralBnp $centralBnp)
    {
        return view('admin.central_bnp.show', compact('centralBnp'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CentralBnp  $centralBnp
     * @return \Illuminate\Http\Response
     */
    public function edit(CentralBnp $centralBnp)
    {
        return view('admin.central_bnp.edit', compact('centralBnp'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CentralBnp  $centralBnp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CentralBnp $centralBnp)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'short_description_en' => 'nullable|string',
            'short_description_bn' => 'nullable|string',
            'content_en' => 'nullable|string',
            'content_bn' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'nullable|in:0,1',
        ]);

        if ($request->hasFile('image')) {
            // remove old image
            if ($centralBnp->image && file_exists(public_path($centralBnp->image))) {
                @unlink(public_path($centralBnp->image));
            }
            $validated['image'] = $this->handleImageUpload($request->file('image'));
        }

        // Decode content if it's present to avoid double-encoded HTML
        if (!empty($validated['content_en'])) {
            $validated['content_en'] = html_entity_decode($validated['content_en'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        if (!empty($validated['content_bn'])) {
            $validated['content_bn'] = html_entity_decode($validated['content_bn'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        $centralBnp->update([
            'title_en' => $validated['title_en'],
            'title_bn' => $validated['title_bn'] ?? null,
            'short_description_en' => $validated['short_description_en'] ?? null,
            'short_description_bn' => $validated['short_description_bn'] ?? null,
            'content_en' => $validated['content_en'] ?? null,
            'content_bn' => $validated['content_bn'] ?? null,
            'image' => $validated['image'] ?? $centralBnp->image,
            'status' => $validated['status'] ?? $centralBnp->status,
        ]);

        return redirect()->route('admin.central_bnp.index')->with('success', 'Central BNP updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CentralBnp  $centralBnp
     * @return \Illuminate\Http\Response
     */
    public function destroy(CentralBnp $centralBnp)
    {
        if ($centralBnp->image && file_exists(public_path($centralBnp->image))) {
            @unlink(public_path($centralBnp->image));
        }
        $centralBnp->delete();
        return redirect()->route('admin.central_bnp.index')->with('success', 'Central BNP deleted successfully.');
    }

    /**
     * Handle image upload and convert to webp when possible.
     * Returns stored relative path (public/) or null.
     */
    protected function handleImageUpload($image)
    {
        $uploadDir = public_path('uploads/central_bnp');
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
                        return 'uploads/central_bnp/' . $webpName;
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
        return 'uploads/central_bnp/' . $origName;
    }

    /**
     * Ensure slug is unique. If exists, append -1, -2, ...
     */
    protected function makeUniqueSlug($baseSlug, $excludeId = null)
    {
        $slug = Str::slug($baseSlug);
        $original = $slug;
        $i = 1;
        while (true) {
            $query = CentralBnp::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            if (!$query->exists()) {
                return $slug;
            }
            $slug = $original . '-' . $i;
            $i++;
        }
    }



    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {

            $file = $request->file('upload');
            $filename = time() . '_' . $file->getClientOriginalName();

            $folder = public_path('uploads/central_bnp');
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $file->move($folder, $filename);

            return response()->json([
                'uploaded' => 1,
                'fileName' => $filename,
                'url' => asset('uploads/central_bnp/' . $filename)
            ]);
        }

        return response()->json(['uploaded' => 0]);
    }
}


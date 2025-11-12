<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::latest()->get();
        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.news.create');
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
            'slug' => 'nullable|string|max:255|unique:news,slug',
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

        $news = News::create([
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

        return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug,' . $news->id,
            'short_description_en' => 'nullable|string',
            'short_description_bn' => 'nullable|string',
            'content_en' => 'nullable|string',
            'content_bn' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'nullable|in:0,1',
        ]);

        if ($request->hasFile('image')) {
            // remove old image
            if ($news->image && file_exists(public_path($news->image))) {
                @unlink(public_path($news->image));
            }
            $validated['image'] = $this->handleImageUpload($request->file('image'));
        }
        // ensure slug
        $slug = $validated['slug'] ?? Str::slug($validated['title_en']);
        $slug = $this->makeUniqueSlug($slug, $news->id);

        $news->update([
            'title_en' => $validated['title_en'],
            'title_bn' => $validated['title_bn'] ?? null,
            'slug' => $slug,
            'short_description_en' => $validated['short_description_en'] ?? null,
            'short_description_bn' => $validated['short_description_bn'] ?? null,
            'content_en' => $validated['content_en'] ?? null,
            'content_bn' => $validated['content_bn'] ?? null,
            'image' => $validated['image'] ?? $news->image,
            'status' => $validated['status'] ?? $news->status,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        if ($news->image && file_exists(public_path($news->image))) {
            @unlink(public_path($news->image));
        }
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }

    /**
     * Handle image upload and convert to webp when possible.
     * Returns stored relative path (public/) or null.
     */
    protected function handleImageUpload($image)
    {
        $uploadDir = public_path('uploads/news');
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
                        return 'uploads/news/' . $webpName;
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
        return 'uploads/news/' . $origName;
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
            $query = News::where('slug', $slug);
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
}

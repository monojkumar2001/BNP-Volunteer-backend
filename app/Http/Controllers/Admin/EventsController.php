<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class EventsController extends Controller
{
    /** Display a listing of events */
    public function index()
    {
        $events = Events::latest()->get();
        return view('admin.event.index', compact('events'));
    }

    /** Show the form for creating a new event */
    public function create()
    {
        return view('admin.event.create');
    }

    /** Store a newly created event in storage */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:events,slug',
            'short_description_en' => 'nullable|string',
            'short_description_bn' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'event_date' => 'nullable|date',
            'event_time' => 'nullable|date_format:H:i',
            'location_en' => 'nullable|string|max:255',
            'location_bn' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_url' => 'nullable|url',
            'status' => 'nullable|boolean',
        ]);

        // ✅ Image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->handleImageUpload($request->file('image'));
        }

        // ✅ Slug generation
        $slug = $validated['slug'] ?? Str::slug($validated['title_en']);
        $slug = $this->makeUniqueSlug($slug);

        // ✅ Create event - only add fields that exist in validated
        $eventData = [
            'title_en' => $validated['title_en'],
            'title_bn' => $validated['title_bn'] ?? null,
            'short_description_en' => $validated['short_description_en'] ?? null,
            'short_description_bn' => $validated['short_description_bn'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'description_bn' => $validated['description_bn'] ?? null,
            'event_date' => $validated['event_date'] ?? null,
            'event_time' => $validated['event_time'] ?? null,
            'location_en' => $validated['location_en'] ?? null,
            'location_bn' => $validated['location_bn'] ?? null,
            'image' => $imagePath,
            'video_url' => $validated['video_url'] ?? null,
            'slug' => $slug,
            'status' => $validated['status'] ? true : false,
        ];

        Events::create($eventData);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    /** Display the specified event */
    public function show(Events $event)
    {
        return view('admin.event.show', compact('event'));
    }

    /** Show the form for editing the specified event */
    public function edit($id)
    {
        $event = Events::findOrFail($id);
        return view('admin.event.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'short_description_en' => 'nullable|string',
            'short_description_bn' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'event_date' => 'nullable',
            'event_time' => 'nullable',
            'location_en' => 'nullable|string|max:255',
            'location_bn' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_url' => 'nullable|url',
            'status' => 'nullable|boolean',
        ]);


        $event = Events::findOrFail($id);


        // ✅ Prepare update data
        $updateData = [
            'title_en' => $validated['title_en'],
            'title_bn' => $validated['title_bn'] ?? null,
            'short_description_en' => $validated['short_description_en'] ?? null,
            'short_description_bn' => $validated['short_description_bn'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'description_bn' => $validated['description_bn'] ?? null,
            'event_date' => $validated['event_date'] ?? null,
            'event_time' => $validated['event_time'] ?? null,
            'location_en' => $validated['location_en'] ?? null,
            'location_bn' => $validated['location_bn'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'status' => isset($validated['status']) ? (bool) $validated['status'] : false,
        ];

        // ✅ Handle image upload
        if ($request->hasFile('image')) {
            if ($event->image && \File::exists(public_path($event->image))) {
                \File::delete(public_path($event->image));
            }

            $updateData['image'] = $this->handleImageUpload($request->file('image'));
        }

        // ✅ Update the event
        $event->update($updateData);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }



    /** Remove the specified event from storage */
    public function destroy(Events $event)
    {
        if ($event->image && File::exists(public_path($event->image))) {
            File::delete(public_path($event->image));
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /** Generate unique slug */
    protected function makeUniqueSlug($baseSlug, $excludeId = null)
    {
        $slug = Str::slug($baseSlug);
        $original = $slug;
        $i = 1;

        while (
            Events::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }

    /** Handle image upload and convert to WebP */
    protected function handleImageUpload($image)
    {
        $uploadDir = public_path('uploads/events');

        // Create directory if it doesn't exist
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        $baseName = time() . '_' . uniqid();
        $webpName = $baseName . '.webp';
        $webpPath = $uploadDir . '/' . $webpName;

        try {
            // Get image content
            $imageContent = file_get_contents($image->getRealPath());
            if (!$imageContent) {
                throw new \Exception('Could not read uploaded file');
            }

            // Try to convert to WebP
            $imageResource = @imagecreatefromstring($imageContent);

            if ($imageResource && function_exists('imagewebp')) {
                try {
                    $ext = strtolower($image->getClientOriginalExtension() ?? 'jpg');

                    // Convert palette/indexed images to truecolor
                    if (!imageistruecolor($imageResource)) {
                        if (function_exists('imagepalettetotruecolor')) {
                            @imagepalettetotruecolor($imageResource);
                        } else {
                            // Manual conversion for older PHP
                            $width = imagesx($imageResource);
                            $height = imagesy($imageResource);
                            $trueColorImage = imagecreatetruecolor($width, $height);

                            // Preserve transparency for PNG/GIF
                            if (in_array($ext, ['png', 'gif'])) {
                                imagealphablending($trueColorImage, false);
                                imagesavealpha($trueColorImage, true);
                                $transparent = imagecolorallocatealpha($trueColorImage, 0, 0, 0, 127);
                                imagefilledrectangle($trueColorImage, 0, 0, $width, $height, $transparent);
                            }

                            imagecopyresampled($trueColorImage, $imageResource, 0, 0, 0, 0, $width, $height, $width, $height);
                            imagedestroy($imageResource);
                            $imageResource = $trueColorImage;
                        }
                    } else {
                        // Preserve transparency for PNG
                        if ($ext === 'png') {
                            imagealphablending($imageResource, false);
                            imagesavealpha($imageResource, true);
                        }
                    }

                    // Convert to WebP
                    $webpResult = @imagewebp($imageResource, $webpPath, 80);
                    imagedestroy($imageResource);

                    if ($webpResult) {
                        return 'uploads/events/' . $webpName;
                    }
                } catch (\Throwable $e) {
                    Log::error('WebP conversion error: ' . $e->getMessage());
                }
            }
        } catch (\Throwable $e) {
            Log::error('Image processing error: ' . $e->getMessage());
        }

        // Fallback: save original file if WebP conversion fails
        $originalExt = $image->getClientOriginalExtension();
        $originalName = $baseName . '.' . $originalExt;
        $image->move($uploadDir, $originalName);

        return 'uploads/events/' . $originalName;
    }
}

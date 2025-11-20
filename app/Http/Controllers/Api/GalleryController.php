<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryCategory;

class GalleryController extends Controller
{
    /**
     * Display a listing of galleries with categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get all active categories with their galleries
        $categories = GalleryCategory::where('status', 1)
            ->with(['galleries' => function($query) {
                $query->where('status', 1)->latest();
            }])
            ->get();

        // Also get all galleries for "all" category
        $allGalleries = Gallery::where('status', 1)
            ->with('category')
            ->latest()
            ->get()
            ->map(function($gallery) {
                return [
                    'id' => $gallery->id,
                    'image' => $gallery->image ? asset($gallery->image) : null,
                    'title_en' => $gallery->title_en,
                    'title_bn' => $gallery->title_bn,
                    'category_id' => $gallery->gallery_category_id,
                    'category_slug' => $gallery->category->slug ?? null,
                ];
            });

        // Format categories with their galleries
        $formattedCategories = $categories->map(function($category) {
            return [
                'id' => $category->id,
                'name_en' => $category->name_en,
                'name_bn' => $category->name_bn,
                'slug' => $category->slug,
                'galleries' => $category->galleries->map(function($gallery) {
                    return [
                        'id' => $gallery->id,
                        'image' => $gallery->image ? asset($gallery->image) : null,
                        'title_en' => $gallery->title_en,
                        'title_bn' => $gallery->title_bn,
                    ];
                }),
            ];
        });

        return response()->json([
            'categories' => $formattedCategories,
            'all_galleries' => $allGalleries,
        ], 200);
    }

    /**
     * Get galleries by category slug.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCategory($slug)
    {
        $category = GalleryCategory::where('slug', $slug)
            ->where('status', 1)
            ->with(['galleries' => function($query) {
                $query->where('status', 1)->latest();
            }])
            ->first();

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        $galleries = $category->galleries->map(function($gallery) {
            return [
                'id' => $gallery->id,
                'image' => $gallery->image ? asset($gallery->image) : null,
                'title_en' => $gallery->title_en,
                'title_bn' => $gallery->title_bn,
            ];
        });

        return response()->json([
            'category' => [
                'id' => $category->id,
                'name_en' => $category->name_en,
                'name_bn' => $category->name_bn,
                'slug' => $category->slug,
            ],
            'galleries' => $galleries,
        ], 200);
    }
}


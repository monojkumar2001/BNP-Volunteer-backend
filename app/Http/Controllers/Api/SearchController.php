<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Events;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search across all content types (News, Events)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $language = $request->get('lang', 'en'); // bn or en

        if (empty($query)) {
            return response()->json([
                'news' => [],
                'events' => [],
                'total' => 0,
            ], 200);
        }

        $searchTerm = '%' . $query . '%';

        // Search in News
        $newsQuery = News::where('status', 1);
        if ($language === 'bn') {
            $newsQuery->where(function($q) use ($searchTerm) {
                $q->where('title_bn', 'LIKE', $searchTerm)
                  ->orWhere('content_bn', 'LIKE', $searchTerm)
                  ->orWhere('short_description_bn', 'LIKE', $searchTerm);
            });
        } else {
            $newsQuery->where(function($q) use ($searchTerm) {
                $q->where('title_en', 'LIKE', $searchTerm)
                  ->orWhere('content_en', 'LIKE', $searchTerm)
                  ->orWhere('short_description_en', 'LIKE', $searchTerm);
            });
        }
        $news = $newsQuery->latest()->limit(10)->get()->map(function($item) use ($language) {
            return [
                'id' => $item->id,
                'type' => 'news',
                'title' => $language === 'bn' ? ($item->title_bn ?: $item->title_en) : ($item->title_en ?: $item->title_bn),
                'description' => $language === 'bn' 
                    ? ($item->short_description_bn ?: $item->short_description_en ?: substr(strip_tags($item->content_bn ?: $item->content_en), 0, 150))
                    : ($item->short_description_en ?: $item->short_description_bn ?: substr(strip_tags($item->content_en ?: $item->content_bn), 0, 150)),
                'slug' => $item->slug,
                'image' => $item->image ? asset($item->image) : null,
                'url' => '/news/' . $item->slug,
                'created_at' => $item->created_at,
            ];
        });

        // Search in Events
        $eventsQuery = Events::where('status', 1);
        if ($language === 'bn') {
            $eventsQuery->where(function($q) use ($searchTerm) {
                $q->where('title_bn', 'LIKE', $searchTerm)
                  ->orWhere('description_bn', 'LIKE', $searchTerm)
                  ->orWhere('short_description_bn', 'LIKE', $searchTerm)
                  ->orWhere('location_bn', 'LIKE', $searchTerm);
            });
        } else {
            $eventsQuery->where(function($q) use ($searchTerm) {
                $q->where('title_en', 'LIKE', $searchTerm)
                  ->orWhere('description_en', 'LIKE', $searchTerm)
                  ->orWhere('short_description_en', 'LIKE', $searchTerm)
                  ->orWhere('location_en', 'LIKE', $searchTerm);
            });
        }
        $events = $eventsQuery->latest()->limit(10)->get()->map(function($item) use ($language) {
            return [
                'id' => $item->id,
                'type' => 'event',
                'title' => $language === 'bn' ? ($item->title_bn ?: $item->title_en) : ($item->title_en ?: $item->title_bn),
                'description' => $language === 'bn' 
                    ? ($item->short_description_bn ?: $item->short_description_en ?: substr(strip_tags($item->description_bn ?: $item->description_en), 0, 150))
                    : ($item->short_description_en ?: $item->short_description_bn ?: substr(strip_tags($item->description_en ?: $item->description_bn), 0, 150)),
                'slug' => $item->slug,
                'image' => $item->image ? asset($item->image) : null,
                'url' => '/events/' . $item->slug,
                'event_date' => $item->event_date,
                'created_at' => $item->created_at,
            ];
        });

        $allResults = $news->merge($events)->sortByDesc('created_at')->take(20)->values();

        return response()->json([
            'news' => $news,
            'events' => $events,
            'all' => $allResults,
            'total' => $news->count() + $events->count(),
            'query' => $query,
        ], 200);
    }
}


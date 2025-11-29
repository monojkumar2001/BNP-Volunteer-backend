<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Events;
use App\Models\CentralBnp;
use App\Http\Controllers\Api\StaticContentSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Elasticsearch-like search across all content types (News, Events, Central BNP)
     * Supports multi-word search, partial matches, and relevance scoring
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));
        $language = $request->get('lang', 'en'); // bn or en

        if (empty($query) || mb_strlen($query, 'UTF-8') < 1) {
            return response()->json([
                'news' => [],
                'events' => [],
                'central_bnp' => [],
                'all' => [],
                'total' => 0,
            ], 200);
        }

        // Clean query - preserve original for display but use for search
        $originalQuery = $query;
        
        // Split query into words for better search
        $searchWords = preg_split('/\s+/', $query);
        $searchWords = array_filter($searchWords, function($word) {
            return mb_strlen(trim($word), 'UTF-8') > 0;
        });
        
        // If no words after filtering, use original query
        if (empty($searchWords)) {
            $searchWords = [$query];
        }

        // Search in News
        $news = $this->searchNews($searchWords, $query, $language);
        
        // Search in Events
        $events = $this->searchEvents($searchWords, $query, $language);
        
        // Search in Central BNP
        $centralBnp = $this->searchCentralBnp($searchWords, $query, $language);
        
        // Search in Static Content
        $staticContent = StaticContentSearch::search($query, $language);

        // Merge all results and sort by relevance (title matches first, then by date)
        $allResults = collect($news)
            ->merge($events)
            ->merge($centralBnp)
            ->merge($staticContent)
            ->sortByDesc(function($item) {
                // Sort by relevance score (if exists) or created_at
                return $item['relevance_score'] ?? strtotime($item['created_at']);
            })
            ->take(50)
            ->values()
            ->map(function($item) {
                unset($item['relevance_score']);
                return $item;
            });

        return response()->json([
            'news' => $news,
            'events' => $events,
            'central_bnp' => $centralBnp,
            'static_content' => $staticContent,
            'all' => $allResults,
            'total' => count($news) + count($events) + count($centralBnp) + count($staticContent),
            'query' => $query,
        ], 200);
    }

    /**
     * Search in News with relevance scoring
     */
    private function searchNews($searchWords, $fullQuery, $language)
    {
        $query = News::where('status', 1);
        
        // Search in both languages for better results
        $query->where(function($q) use ($searchWords, $fullQuery, $language) {
            // Search in selected language fields
            $q->where(function($langQ) use ($searchWords, $fullQuery, $language) {
                $titleField = $language === 'bn' ? 'title_bn' : 'title_en';
                $contentField = $language === 'bn' ? 'content_bn' : 'content_en';
                $descField = $language === 'bn' ? 'short_description_bn' : 'short_description_en';
                
                // Full query match (highest priority)
                $fullSearchTerm = '%' . $fullQuery . '%';
                $langQ->where($titleField, 'LIKE', $fullSearchTerm)
                      ->orWhere($contentField, 'LIKE', $fullSearchTerm)
                      ->orWhere($descField, 'LIKE', $fullSearchTerm);
                
                // Individual word matches
                foreach ($searchWords as $word) {
                    $searchTerm = '%' . $word . '%';
                    $langQ->orWhere($titleField, 'LIKE', $searchTerm)
                          ->orWhere($contentField, 'LIKE', $searchTerm)
                          ->orWhere($descField, 'LIKE', $searchTerm);
                }
            });
            
            // Also search in alternate language if current language field is empty
            $altTitleField = $language === 'bn' ? 'title_en' : 'title_bn';
            $altContentField = $language === 'bn' ? 'content_en' : 'content_bn';
            $altDescField = $language === 'bn' ? 'short_description_en' : 'short_description_bn';
            
            $fullSearchTerm = '%' . $fullQuery . '%';
            $q->orWhere(function($altQ) use ($searchWords, $fullQuery, $altTitleField, $altContentField, $altDescField) {
                $altQ->where($altTitleField, 'LIKE', '%' . $fullQuery . '%')
                     ->orWhere($altContentField, 'LIKE', '%' . $fullQuery . '%')
                     ->orWhere($altDescField, 'LIKE', '%' . $fullQuery . '%');
                
                foreach ($searchWords as $word) {
                    $searchTerm = '%' . $word . '%';
                    $altQ->orWhere($altTitleField, 'LIKE', $searchTerm)
                         ->orWhere($altContentField, 'LIKE', $searchTerm)
                         ->orWhere($altDescField, 'LIKE', $searchTerm);
                }
            });
        });

        return $query->latest()->limit(20)->get()->map(function($item) use ($language, $fullQuery) {
            $title = $language === 'bn' ? ($item->title_bn ?: $item->title_en) : ($item->title_en ?: $item->title_bn);
            $description = $language === 'bn' 
                ? ($item->short_description_bn ?: $item->short_description_en ?: substr(strip_tags($item->content_bn ?: $item->content_en), 0, 150))
                : ($item->short_description_en ?: $item->short_description_bn ?: substr(strip_tags($item->content_en ?: $item->content_bn), 0, 150));
            
            // Calculate relevance score (title matches score higher)
            $relevanceScore = 0;
            if (stripos($title, $fullQuery) !== false) {
                $relevanceScore += 100; // Exact title match
            }
            if (stripos($title, $fullQuery) === 0) {
                $relevanceScore += 50; // Starts with query
            }
            foreach (explode(' ', $fullQuery) as $word) {
                if (stripos($title, $word) !== false) {
                    $relevanceScore += 10; // Word in title
                }
            }

            return [
                'id' => $item->id,
                'type' => 'news',
                'title' => $title,
                'description' => $description,
                'slug' => $item->slug,
                'image' => $item->image ? asset($item->image) : null,
                'url' => '/news/' . $item->slug,
                'created_at' => $item->created_at,
                'relevance_score' => $relevanceScore,
            ];
        })->toArray();
    }

    /**
     * Search in Events with relevance scoring
     */
    private function searchEvents($searchWords, $fullQuery, $language)
    {
        $query = Events::where('status', 1);
        
        // Search in both languages for better results
        $query->where(function($q) use ($searchWords, $fullQuery, $language) {
            // Search in selected language fields
            $q->where(function($langQ) use ($searchWords, $fullQuery, $language) {
                $titleField = $language === 'bn' ? 'title_bn' : 'title_en';
                $descField = $language === 'bn' ? 'description_bn' : 'description_en';
                $shortDescField = $language === 'bn' ? 'short_description_bn' : 'short_description_en';
                $locationField = $language === 'bn' ? 'location_bn' : 'location_en';
                
                // Full query match (highest priority)
                $fullSearchTerm = '%' . $fullQuery . '%';
                $langQ->where($titleField, 'LIKE', $fullSearchTerm)
                      ->orWhere($descField, 'LIKE', $fullSearchTerm)
                      ->orWhere($shortDescField, 'LIKE', $fullSearchTerm)
                      ->orWhere($locationField, 'LIKE', $fullSearchTerm);
                
                // Individual word matches
                foreach ($searchWords as $word) {
                    $searchTerm = '%' . $word . '%';
                    $langQ->orWhere($titleField, 'LIKE', $searchTerm)
                          ->orWhere($descField, 'LIKE', $searchTerm)
                          ->orWhere($shortDescField, 'LIKE', $searchTerm)
                          ->orWhere($locationField, 'LIKE', $searchTerm);
                }
            });
            
            // Also search in alternate language
            $altTitleField = $language === 'bn' ? 'title_en' : 'title_bn';
            $altDescField = $language === 'bn' ? 'description_en' : 'description_bn';
            $altShortDescField = $language === 'bn' ? 'short_description_en' : 'short_description_bn';
            $altLocationField = $language === 'bn' ? 'location_en' : 'location_bn';
            
            $q->orWhere(function($altQ) use ($searchWords, $fullQuery, $altTitleField, $altDescField, $altShortDescField, $altLocationField) {
                $altQ->where($altTitleField, 'LIKE', '%' . $fullQuery . '%')
                     ->orWhere($altDescField, 'LIKE', '%' . $fullQuery . '%')
                     ->orWhere($altShortDescField, 'LIKE', '%' . $fullQuery . '%')
                     ->orWhere($altLocationField, 'LIKE', '%' . $fullQuery . '%');
                
                foreach ($searchWords as $word) {
                    $searchTerm = '%' . $word . '%';
                    $altQ->orWhere($altTitleField, 'LIKE', $searchTerm)
                         ->orWhere($altDescField, 'LIKE', $searchTerm)
                         ->orWhere($altShortDescField, 'LIKE', $searchTerm)
                         ->orWhere($altLocationField, 'LIKE', $searchTerm);
                }
            });
        });

        return $query->latest()->limit(20)->get()->map(function($item) use ($language, $fullQuery) {
            $title = $language === 'bn' ? ($item->title_bn ?: $item->title_en) : ($item->title_en ?: $item->title_bn);
            $description = $language === 'bn' 
                ? ($item->short_description_bn ?: $item->short_description_en ?: substr(strip_tags($item->description_bn ?: $item->description_en), 0, 150))
                : ($item->short_description_en ?: $item->short_description_bn ?: substr(strip_tags($item->description_en ?: $item->description_bn), 0, 150));
            
            // Calculate relevance score
            $relevanceScore = 0;
            if (stripos($title, $fullQuery) !== false) {
                $relevanceScore += 100;
            }
            if (stripos($title, $fullQuery) === 0) {
                $relevanceScore += 50;
            }
            foreach (explode(' ', $fullQuery) as $word) {
                if (stripos($title, $word) !== false) {
                    $relevanceScore += 10;
                }
            }

            return [
                'id' => $item->id,
                'type' => 'event',
                'title' => $title,
                'description' => $description,
                'slug' => $item->slug,
                'image' => $item->image ? asset($item->image) : null,
                'url' => '/events/' . $item->slug,
                'event_date' => $item->event_date,
                'created_at' => $item->created_at,
                'relevance_score' => $relevanceScore,
            ];
        })->toArray();
    }

    /**
     * Search in Central BNP with relevance scoring
     */
    private function searchCentralBnp($searchWords, $fullQuery, $language)
    {
        $query = CentralBnp::where('status', 1);
        
        // Search in both languages for better results
        $query->where(function($q) use ($searchWords, $fullQuery, $language) {
            // Search in selected language fields
            $q->where(function($langQ) use ($searchWords, $fullQuery, $language) {
                $titleField = $language === 'bn' ? 'title_bn' : 'title_en';
                $contentField = $language === 'bn' ? 'content_bn' : 'content_en';
                $descField = $language === 'bn' ? 'short_description_bn' : 'short_description_en';
                
                // Full query match (highest priority)
                $fullSearchTerm = '%' . $fullQuery . '%';
                $langQ->where($titleField, 'LIKE', $fullSearchTerm)
                      ->orWhere($contentField, 'LIKE', $fullSearchTerm)
                      ->orWhere($descField, 'LIKE', $fullSearchTerm);
                
                // Individual word matches
                foreach ($searchWords as $word) {
                    $searchTerm = '%' . $word . '%';
                    $langQ->orWhere($titleField, 'LIKE', $searchTerm)
                          ->orWhere($contentField, 'LIKE', $searchTerm)
                          ->orWhere($descField, 'LIKE', $searchTerm);
                }
            });
            
            // Also search in alternate language
            $altTitleField = $language === 'bn' ? 'title_en' : 'title_bn';
            $altContentField = $language === 'bn' ? 'content_en' : 'content_bn';
            $altDescField = $language === 'bn' ? 'short_description_en' : 'short_description_bn';
            
            $q->orWhere(function($altQ) use ($searchWords, $fullQuery, $altTitleField, $altContentField, $altDescField) {
                $altQ->where($altTitleField, 'LIKE', '%' . $fullQuery . '%')
                     ->orWhere($altContentField, 'LIKE', '%' . $fullQuery . '%')
                     ->orWhere($altDescField, 'LIKE', '%' . $fullQuery . '%');
                
                foreach ($searchWords as $word) {
                    $searchTerm = '%' . $word . '%';
                    $altQ->orWhere($altTitleField, 'LIKE', $searchTerm)
                         ->orWhere($altContentField, 'LIKE', $searchTerm)
                         ->orWhere($altDescField, 'LIKE', $searchTerm);
                }
            });
        });

        return $query->latest()->limit(20)->get()->map(function($item) use ($language, $fullQuery) {
            $title = $language === 'bn' ? ($item->title_bn ?: $item->title_en) : ($item->title_en ?: $item->title_bn);
            $description = $language === 'bn' 
                ? ($item->short_description_bn ?: $item->short_description_en ?: substr(strip_tags($item->content_bn ?: $item->content_en), 0, 150))
                : ($item->short_description_en ?: $item->short_description_bn ?: substr(strip_tags($item->content_en ?: $item->content_bn), 0, 150));
            
            // Calculate relevance score
            $relevanceScore = 0;
            if (stripos($title, $fullQuery) !== false) {
                $relevanceScore += 100;
            }
            if (stripos($title, $fullQuery) === 0) {
                $relevanceScore += 50;
            }
            foreach (explode(' ', $fullQuery) as $word) {
                if (stripos($title, $word) !== false) {
                    $relevanceScore += 10;
                }
            }

            return [
                'id' => $item->id,
                'type' => 'central_bnp',
                'title' => $title,
                'description' => $description,
                'slug' => $item->slug,
                'image' => $item->image ? asset($item->image) : null,
                'url' => '/central-bnp/' . $item->slug,
                'created_at' => $item->created_at,
                'relevance_score' => $relevanceScore,
            ];
        })->toArray();
    }
}


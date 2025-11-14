<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class NewsController extends Controller
{

    public function index()
    {
        $news = News::latest()->get();
        return response()->json($news, 200);
    }
    public function singleNews($slug)
    {
        $news = News::where('slug', $slug)->first();

        if (!$news) {
            return response()->json([
                'message' => 'News not found'
            ], 404);
        }

        return response()->json($news, 200);
    }
}

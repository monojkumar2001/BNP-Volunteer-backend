<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en',
        'title_bn',
        'short_description_en',
        'short_description_bn',
        'description_en',
        'description_bn',
        'event_date',
        'event_time',
        'location_en',
        'location_bn',
        'image',
        'video_url',
        'slug',
        'status',
    ];
}

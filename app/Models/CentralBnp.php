<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentralBnp extends Model
{
    use HasFactory;
    
    protected $table = 'central_bnp';
    
    protected $fillable = [
        'title_en',
        'title_bn',
        'slug',
        'short_description_en',
        'short_description_bn',
        'content_en',
        'content_bn',
        'image',
        'status',
    ];
    public function getImageUrlAttribute()
    {
        return url('uploads/central_bnp/' . $this->image);
    }
}


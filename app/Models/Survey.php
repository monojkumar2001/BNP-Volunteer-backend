<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [

        'slug',
        'image',
        'title',
        'description',
        'is_active',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
    ];


    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean', // Add this as well for boolean casting
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }


    public function responses()
        {
            return $this->hasMany(\App\Models\Response::class);
        }

}

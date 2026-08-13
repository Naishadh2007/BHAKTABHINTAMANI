<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'title',
        'description',
        'content',
        'title_gu',
        'description_gu',
        'content_gu',
        'title_en',
        'description_en',
        'content_en',
        'order',
        'status',
        'published_at',
    ];

    protected $casts = [
        'order'        => 'integer',
        'published_at' => 'datetime',
    ];
}

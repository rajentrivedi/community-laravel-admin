<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\InteractsWithMedia;

class Publication extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pdfs')->acceptsMimeTypes(['application/pdf'])->useDisk('public');
    }

    public function community() : BelongsTo
    { 
        return $this->belongsTo(Community::class);
    }
    public function author() : BelongsTo
    { 
        return $this->belongsTo(User::class, 'user_id');
    }
}
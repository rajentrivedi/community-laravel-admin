<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $casts = [
        'published_at'=>'datetime',
        'is_pinned'=>'boolean'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useFallbackUrl('/images/placeholder.jpg')
            ->useFallbackPath(public_path('/images/placeholder.jpg'))
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->useDisk('public');
    }

    public function scopePublished($q)
    { 
        return $q->whereNotNull('published_at')->where('published_at','<=',now()); 
    }
    public function community() : BelongsTo
    { 
        return $this->belongsTo(Community::class);
    }
    public function author() : BelongsTo
    { 
        return $this->belongsTo(User::class,'user_id'); 
    }
}
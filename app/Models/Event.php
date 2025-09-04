<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'lat',
        'lng',
        'start_at',
        'end_at',
        'community_id',
        'user_id',
        'status',
        'max_attendees',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'lat' => 'float',
        'lng' => 'float',
    ];

    public function community(): BelongsTo
    { 
        return $this->belongsTo(Community::class);
    }
    
    public function creator(): BelongsTo
    { 
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function attendees(): BelongsToMany
    { 
        return $this->belongsToMany(User::class, 'event_attendees')->withTimestamps(); 
    }
}

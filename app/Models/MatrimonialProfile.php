<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class MatrimonialProfile extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'family_member_id',
        'gender',
        'age',
        'height',
        'weight',
        'marital_status',
        'education',
        'occupation',
        'annual_income',
        'currency',
        'religion',
        'caste',
        'sub_caste',
        'mother_tongue',
        'country',
        'state',
        'city',
        'about_me',
        'partner_preferences',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'annual_income' => 'decimal:2',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Clear cache when a matrimonial profile is created, updated, or deleted
        static::created(function (MatrimonialProfile $matrimonialProfile) {
            self::clearMatrimonialProfilesCache();
        });

        static::updated(function (MatrimonialProfile $matrimonialProfile) {
            self::clearMatrimonialProfilesCache();
        });

        static::deleted(function (MatrimonialProfile $matrimonialProfile) {
            self::clearMatrimonialProfilesCache();
        });
    }

    /**
     * Clear matrimonial profiles cache
     */
    protected static function clearMatrimonialProfilesCache(): void
    {
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['matrimonial', 'profiles'])->flush();
        }
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
            ->useDisk('public')
            ->onlyKeepLatest(10);
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->keepOriginalImageFormat();
    }

    /**
     * Get the user that owns the matrimonial profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the family member associated with the matrimonial profile.
     */
    public function familyMember(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class);
    }
}
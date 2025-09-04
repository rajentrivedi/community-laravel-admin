<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_no',
    ];

    // Relationships
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function communities()
    {
        // pivot has `role` + timestamps
        return $this->belongsToMany(Community::class)
            ->using(CommunityUser::class)
            ->withPivot(['role'])
            ->withTimestamps();
    }

    public function posts()    
    {
        return $this->hasMany(Post::class);
    }

    public function events()   
    {
        return $this->hasMany(Event::class);
    }

    public function publications()
    {
        return $this->hasMany(Publication::class);
    }

    public function attendingEvents()
    {
        return $this->belongsToMany(Event::class, 'event_attendees')
            ->using(EventAttendee::class)
            ->withPivot(['status'])
            ->withTimestamps();
    }

    public function familyMembers() : HasMany
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function matrimonialProfiles() : HasMany
    {
        return $this->hasMany(MatrimonialProfile::class);
    }

    /**
     * Get the FCM tokens for the user.
     */
    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class);
    }
}


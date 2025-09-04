<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyMember extends Model
{
    /** @use HasFactory<\Database\Factories\FamilyMemberFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'organization',
        'job_title',
        'industry',
        'employment_type',
        'start_date_employment',
        'end_date_employment',
        'is_current_employment',
        'annual_income',
        'currency',
        'city_employment',
        'state_employment',
        'country_employment',
        'degree_level',
        'field_of_study',
        'institution',
        'board_university',
        'start_date_study',
        'end_date_study',
        'currently_studying',
        'grade',
        'city_study',
        'state_study',
        'country_study',
    ];

    protected $casts = [
        'is_current_employment' => 'boolean',
        'currently_studying' => 'boolean',
        'start_date_employment' => 'date',
        'end_date_employment' => 'date',
        'start_date_study' => 'date',
        'end_date_study' => 'date',
        'annual_income' => 'decimal:2',
    ];

    /**
     * Get the user that owns the family member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

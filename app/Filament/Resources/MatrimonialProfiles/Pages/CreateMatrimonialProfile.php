<?php

namespace App\Filament\Resources\MatrimonialProfiles\Pages;

use App\Filament\Resources\MatrimonialProfiles\MatrimonialProfileResource;
use App\Models\MatrimonialProfile;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateMatrimonialProfile extends CreateRecord
{
    protected static string $resource = MatrimonialProfileResource::class;

    public static function afterCreate(MatrimonialProfile $record): void
    {
        // Invalidate cache when a new matrimonial profile is created
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['matrimonial', 'profiles'])->flush();
        }
    }
}
<?php

namespace App\Filament\Resources\MatrimonialProfiles\Pages;

use App\Filament\Resources\MatrimonialProfiles\MatrimonialProfileResource;
use App\Models\MatrimonialProfile;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditMatrimonialProfile extends EditRecord
{
    protected static string $resource = MatrimonialProfileResource::class;

    protected function afterSave(): void
    {
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['matrimonial', 'profiles'])->flush();
        }
    }
}
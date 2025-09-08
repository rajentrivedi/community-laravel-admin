<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function afterCreate(): void
    {
        // Invalidate events cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['events'])->flush();
        }
    }
}
<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function afterSave(): void
    {
        // Invalidate events cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['events'])->flush();
        }
    }
}
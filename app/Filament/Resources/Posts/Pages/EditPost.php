<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function afterSave(): void
    {
        // Invalidate news cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['news'])->flush();
        }
    }
}
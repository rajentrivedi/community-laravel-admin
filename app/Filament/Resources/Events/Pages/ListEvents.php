<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Cache;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function afterTableBulkActionProcessed(): void
    {
        // Invalidate events cache after bulk actions (including delete)
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['events'])->flush();
        }
    }
}
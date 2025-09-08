<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Cache;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function afterTableBulkActionProcessed(): void
    {
        // Invalidate news cache after bulk actions (including delete)
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['news'])->flush();
        }
    }
}
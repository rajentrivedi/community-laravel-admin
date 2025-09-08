<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Cache;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function afterTableBulkActionProcessed(): void
    {
        // Invalidate users cache after bulk actions (including delete)
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['users'])->flush();
        }
    }
}

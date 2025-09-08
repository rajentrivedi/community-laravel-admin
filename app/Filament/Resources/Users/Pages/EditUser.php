<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->after(function () {
                    // Invalidate users cache after delete
                    if (method_exists(Cache::getStore(), 'tags')) {
                        Cache::tags(['users'])->flush();
                    }
                }),
        ];
    }

    protected function afterSave(): void
    {
        // Invalidate users cache after save
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['users'])->flush();
        }
    }
}

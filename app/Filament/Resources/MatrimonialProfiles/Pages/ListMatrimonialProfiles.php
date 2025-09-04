<?php

namespace App\Filament\Resources\MatrimonialProfiles\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\MatrimonialProfiles\MatrimonialProfileResource;

class ListMatrimonialProfiles extends ListRecords
{
    protected static string $resource = MatrimonialProfileResource::class;

     protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
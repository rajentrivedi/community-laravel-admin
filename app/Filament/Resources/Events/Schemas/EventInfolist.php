<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('community.name'),
                TextEntry::make('creator.name'),
                TextEntry::make('title'),
                TextEntry::make('description'),
                TextEntry::make('start_at')
                    ->dateTime(),
                TextEntry::make('end_at')
                    ->dateTime(),
                TextEntry::make('venue_name'),
                TextEntry::make('address'),
                TextEntry::make('city'),
                TextEntry::make('state'),
                TextEntry::make('pincode'),
                TextEntry::make('lat'),
                TextEntry::make('lng'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
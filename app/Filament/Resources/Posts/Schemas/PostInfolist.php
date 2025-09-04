<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Schemas\Schema;

class PostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('community.name'),
                TextEntry::make('author.name')
                    ->label('Author'),
                TextEntry::make('title'),
                TextEntry::make('body'),
                TextEntry::make('type'),
                TextEntry::make('published_at')
                    ->dateTime(),
                TextEntry::make('is_pinned')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'),
                SpatieMediaLibraryImageEntry::make('images')
                    ->collection('images')
                    ->circular()
                    ->size(200),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
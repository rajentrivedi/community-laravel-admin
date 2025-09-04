<?php

namespace App\Filament\Resources\Publications\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;

class PublicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('title'),
                        TextEntry::make('community.name'),
                        TextEntry::make('author.name'),
                        TextEntry::make('description'),
                        TextEntry::make('published_at')
                            ->dateTime(),
                        SpatieMediaLibraryImageEntry::make('pdfs')
                            ->collection('pdfs')
                            ->label('PDF File'),
                    ]),
            ]);
    }
}
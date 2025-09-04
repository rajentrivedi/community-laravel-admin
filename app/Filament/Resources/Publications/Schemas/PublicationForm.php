<?php

namespace App\Filament\Resources\Publications\Schemas;

use App\Models\Community;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PublicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Select::make('community_id')
                //     ->label('Community')
                //     ->options(Community::all()->pluck('name', 'id'))
                //     ->searchable(),
                Select::make('user_id')
                    ->label('Author')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull(),
                DateTimePicker::make('published_at'),
                SpatieMediaLibraryFileUpload::make('pdfs')
                    ->collection('pdfs')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxFiles(1)
                    ->minFiles(1)
                    ->required(),
            ]);
    }
}
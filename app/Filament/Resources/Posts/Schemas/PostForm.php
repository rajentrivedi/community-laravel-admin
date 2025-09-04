<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Community;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('community_id')
                    ->label('Community')
                    ->options(Community::all()->pluck('name', 'id'))
                    ->searchable(),
                Select::make('user_id')
                    ->label('Author')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('body')
                    ->columnSpanFull(),
                Select::make('type')
                    ->options([
                        'news' => 'News',
                        'announcement' => 'Announcement',
                        'event' => 'Event',
                    ])
                    ->required(),
                DateTimePicker::make('published_at'),
                Toggle::make('is_pinned')
                    ->label('Pinned'),
                SpatieMediaLibraryFileUpload::make('images')
                    ->collection('images')
                    ->multiple()
                    ->image()
                    ->imageEditor(),
            ]);
    }
}
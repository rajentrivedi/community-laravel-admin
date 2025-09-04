<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Models\Community;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EventForm
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
                    ->label('Creator')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull(),
                DateTimePicker::make('start_at')
                    ->required(),
                DateTimePicker::make('end_at'),
                TextInput::make('venue_name')
                    ->maxLength(255),
                TextInput::make('address')
                    ->maxLength(255),
                TextInput::make('city')
                    ->maxLength(255),
                TextInput::make('state')
                    ->maxLength(255),
                TextInput::make('pincode')
                    ->label('Pincode')
                    ->maxLength(10),
                TextInput::make('lat')
                    ->numeric()
                    ->minValue(-90)
                    ->maxValue(90),
                TextInput::make('lng')
                    ->numeric()
                    ->minValue(-180)
                    ->maxValue(180),
            ]);
    }
}
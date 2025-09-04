<?php

namespace App\Filament\Resources\MatrimonialProfiles\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;

class MatrimonialProfileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->columns(2)
                    ->components([
                        TextEntry::make('user.name')
                            ->label('User'),
                        TextEntry::make('marital_status'),
                        TextEntry::make('age'),
                        TextEntry::make('height')
                            ->formatStateUsing(fn ($state) => $state ? $state . ' cm' : null),
                        TextEntry::make('weight')
                            ->formatStateUsing(fn ($state) => $state ? $state . ' kg' : null),
                        TextEntry::make('religion'),
                        TextEntry::make('caste'),
                        TextEntry::make('sub_caste'),
                        TextEntry::make('mother_tongue'),
                    ]),

                Section::make('Location')
                    ->columns(3)
                    ->components([
                        TextEntry::make('country'),
                        TextEntry::make('state'),
                        TextEntry::make('city'),
                    ]),

                Section::make('Education & Career')
                    ->columns(2)
                    ->components([
                        TextEntry::make('education'),
                        TextEntry::make('occupation'),
                        TextEntry::make('annual_income')
                            ->formatStateUsing(fn ($state, $record) => $state ? $record->currency . ' ' . number_format($state, 2) : null),
                    ]),

                Section::make('Profile Images')
                    ->components([
                        SpatieMediaLibraryImageEntry::make('profile_images')
                            ->collection('profile_images')
                            ->columns(5),
                    ]),

                Section::make('About')
                    ->columns(1)
                    ->components([
                        TextEntry::make('about_me')
                            ->html(),
                        TextEntry::make('partner_preferences')
                            ->html(),
                        TextEntry::make('is_active')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                    ]),
            ]);
    }
}
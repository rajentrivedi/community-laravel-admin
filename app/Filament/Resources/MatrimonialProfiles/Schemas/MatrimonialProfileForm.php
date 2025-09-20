<?php

namespace App\Filament\Resources\MatrimonialProfiles\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class MatrimonialProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->columns(3)
                    ->components([
                        Section::make('Basic Information')
                            ->columnSpan(2)
                            ->components([
                                Select::make('user_id')
                                    ->label('User')
                                    ->options(User::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                Select::make('marital_status')
                                    ->options([
                                        'single' => 'Single',
                                        'divorced' => 'Divorced',
                                        'widowed' => 'Widowed',
                                        'separated' => 'Separated',
                                    ])
                                    ->required(),
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('age')
                                            ->numeric()
                                            ->minValue(18)
                                            ->maxValue(100)
                                            ->required(),
                                        TextInput::make('height')
                                            ->label('Height (cm)')
                                            ->numeric()
                                            ->minValue(100)
                                            ->maxValue(250),
                                    ]),
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('weight')
                                            ->label('Weight (kg)')
                                            ->numeric()
                                            ->minValue(30)
                                            ->maxValue(200),
                                        TextInput::make('religion')
                                            ->maxLength(255),
                                    ]),
                                TextInput::make('caste')
                                    ->maxLength(255),
                                TextInput::make('sub_caste')
                                    ->maxLength(255),
                                TextInput::make('mother_tongue')
                                    ->maxLength(255),
                            ])->columnSpanFull(),

                        Section::make('Location')
                            ->columnSpan(1)
                            ->components([
                                TextInput::make('country')
                                    ->maxLength(255),
                                TextInput::make('state')
                                    ->maxLength(255),
                                TextInput::make('city')
                                    ->maxLength(255),
                            ])->columnSpanFull(),

                        Section::make('Education & Career')
                            ->columnSpan(2)
                            ->components([
                                TextInput::make('education')
                                    ->maxLength(255),
                                TextInput::make('occupation')
                                    ->maxLength(255),
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('annual_income')
                                            ->numeric()
                                            ->prefix('₹'),
                                        Select::make('currency')
                                            ->options([
                                                'INR' => 'Indian Rupee (₹)',
                                                'USD' => 'US Dollar ($)',
                                                'EUR' => 'Euro (€)',
                                                'GBP' => 'British Pound (£)',
                                            ])
                                            ->default('INR'),
                                    ]),
                            ])->columnSpanFull(),

                        Section::make('Profile Images')
                            ->columnSpan(1)
                            ->components([
                                SpatieMediaLibraryFileUpload::make('profile_images')
                                    ->collection('profile_images')
                                    ->disk('supabase')
                                    ->multiple()
                                    ->maxFiles(10)
                                    ->visibility('public')
                                    ->reorderable(),
                            ])->columnSpanFull(),

                        Section::make('About')
                            ->columnSpan(3)
                            ->components([
                                Textarea::make('about_me')
                                    ->maxLength(1000)
                                    ->columnSpanFull(),
                                Textarea::make('partner_preferences')
                                    ->maxLength(1000)
                                    ->columnSpanFull(),
                                Toggle::make('is_active')
                                    ->default(true),
                            ])->columnSpanFull(),
                    ]),
            ]);
    }
}
<?php

namespace App\Filament\Resources\MatrimonialProfiles\Tables;

use App\Models\User;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\DeleteBulkAction;

class MatrimonialProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('age')
                    ->sortable(),
                TextColumn::make('religion')
                    ->searchable(),
                TextColumn::make('caste')
                    ->searchable(),
                TextColumn::make('occupation')
                    ->searchable(),
                TextColumn::make('city')
                    ->searchable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                SelectFilter::make('marital_status')
                    ->options([
                        'single' => 'Single',
                        'divorced' => 'Divorced',
                        'widowed' => 'Widowed',
                        'separated' => 'Separated',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                // DeleteBulkAction::make(),
            ]);
    }
}
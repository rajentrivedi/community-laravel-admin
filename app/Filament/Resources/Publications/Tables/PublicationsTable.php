<?php

namespace App\Filament\Resources\Publications\Tables;

use App\Models\User;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class PublicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('community.name')
                    ->searchable(),
                TextColumn::make('author.name')
                    ->searchable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('pdfs')
                    ->collection('pdfs')
                    ->label('PDF Preview'),
            ])
            ->filters([
                SelectFilter::make('author')
                    ->relationship('author', 'name'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                // DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
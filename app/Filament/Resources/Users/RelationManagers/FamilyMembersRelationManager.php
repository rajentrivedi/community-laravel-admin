<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\FamilyMembers\Schemas\FamilyMemberForm;
use Filament\Support\Enums\Width;

class FamilyMembersRelationManager extends RelationManager
{
    protected static string $relationship = 'familyMembers';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Family Members';
    }

    public function form(Schema $schema): Schema
    {
        return FamilyMemberForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('relationship'),
                Tables\Columns\TextColumn::make('organization'),
                Tables\Columns\TextColumn::make('job_title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalWidth(Width::SevenExtraLarge),
            ])
            ->actions([
               EditAction::make()
                    ->modalWidth(Width::SevenExtraLarge),
               DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
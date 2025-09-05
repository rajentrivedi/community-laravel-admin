<?php

namespace App\Filament\Resources\MatrimonialProfiles;

use App\Filament\Resources\MatrimonialProfiles\Pages\CreateMatrimonialProfile;
use App\Filament\Resources\MatrimonialProfiles\Pages\EditMatrimonialProfile;
use App\Filament\Resources\MatrimonialProfiles\Pages\ListMatrimonialProfiles;
use App\Filament\Resources\MatrimonialProfiles\Pages\ViewMatrimonialProfile;
use App\Filament\Resources\MatrimonialProfiles\Schemas\MatrimonialProfileForm;
use App\Filament\Resources\MatrimonialProfiles\Schemas\MatrimonialProfileInfolist;
use App\Filament\Resources\MatrimonialProfiles\Tables\MatrimonialProfilesTable;
use App\Models\MatrimonialProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MatrimonialProfileResource extends Resource
{
    protected static ?string $model = MatrimonialProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return MatrimonialProfileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MatrimonialProfileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MatrimonialProfilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }



    public static function getPages(): array
    {
        return [
            'index' => ListMatrimonialProfiles::route('/'),
            'create' => CreateMatrimonialProfile::route('/create'),
            'view' => ViewMatrimonialProfile::route('/{record}'),
            'edit' => EditMatrimonialProfile::route('/{record}/edit'),
        ];
    }
}
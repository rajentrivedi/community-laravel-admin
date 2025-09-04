<?php

namespace App\Filament\Resources\FamilyMembers\Pages;

use Filament\Support\Enums\Width;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Resources\Pages\EditRecord\Concerns\HasWizard;
use App\Filament\Resources\FamilyMembers\FamilyMemberResource;

class EditFamilyMember extends EditRecord
{
    use HasWizard;

    protected static string $resource = FamilyMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getMaxWidth(): Width
    {
        return Width::SevenExtraLarge;
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Basic Information')
                ->description('Enter the basic information of the family member')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Select::make('relationship')
                        ->options([
                            'father' => 'Father',
                            'mother' => 'Mother',
                            'spouse' => 'Spouse',
                            'child' => 'Child',
                            'sibling' => 'Sibling',
                            'other' => 'Other',
                        ])
                        ->required(),
                    DatePicker::make('date_of_birth'),
                    TextInput::make('phone')
                        ->tel(),
                    TextInput::make('email')
                        ->email(),
                ])
                ->columns(2),

            Step::make('Employment Details')
                ->description('Enter the employment details of the family member')
                ->schema([
                    TextInput::make('organization')
                        ->maxLength(255),
                    TextInput::make('job_title')
                        ->maxLength(255),
                    TextInput::make('industry')
                        ->maxLength(255),
                    Select::make('employment_type')
                        ->options([
                            'full_time' => 'Full-time',
                            'part_time' => 'Part-time',
                            'self_employed' => 'Self-employed',
                            'business' => 'Business',
                            'govt' => 'Government',
                            'psu' => 'PSU',
                            'other' => 'Other',
                        ]),
                    DatePicker::make('start_date_employment'),
                    DatePicker::make('end_date_employment'),
                    Fieldset::make('Current Employment')
                        ->schema([
                            Select::make('is_current_employment')
                                ->boolean(),
                        ]),
                    TextInput::make('annual_income')
                        ->numeric()
                        ->prefix('₹'),
                    TextInput::make('currency')
                        ->default('INR')
                        ->maxLength(3),
                    TextInput::make('city_employment')
                        ->label('City')
                        ->maxLength(255),
                    TextInput::make('state_employment')
                        ->label('State')
                        ->maxLength(255),
                    TextInput::make('country_employment')
                        ->label('Country')
                        ->default('India')
                        ->maxLength(255),
                ])
                ->columns(2),

            Step::make('Education Details')
                ->description('Enter the education details of the family member')
                ->schema([
                    Select::make('degree_level')
                        ->options([
                            '10th' => '10th',
                            '12th' => '12th',
                            'diploma' => 'Diploma',
                            'bachelors' => 'Bachelors',
                            'masters' => 'Masters',
                            'phd' => 'PhD',
                            'other' => 'Other',
                        ])
                        ->searchable(),
                    TextInput::make('field_of_study')
                        ->maxLength(255),
                    TextInput::make('institution')
                        ->maxLength(255),
                    TextInput::make('board_university')
                        ->label('Board/University')
                        ->maxLength(255),
                    DatePicker::make('start_date_study'),
                    DatePicker::make('end_date_study'),
                    Fieldset::make('Currently Studying')
                        ->schema([
                            Select::make('currently_studying')
                                ->boolean(),
                        ]),
                    TextInput::make('grade')
                        ->maxLength(255),
                    TextInput::make('city_study')
                        ->label('City')
                        ->maxLength(255),
                    TextInput::make('state_study')
                        ->label('State')
                        ->maxLength(255),
                    TextInput::make('country_study')
                        ->label('Country')
                        ->default('India')
                        ->maxLength(255),
                ])
                ->columns(2),
        ];
    }
}

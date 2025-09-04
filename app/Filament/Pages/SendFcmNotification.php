<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use App\Models\Notification;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Services\NotificationService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Services\FirebaseMessagingService;
use Filament\Actions\Contracts\HasActions;
use Kreait\Firebase\Messaging\CloudMessage;
use Filament\Notifications\Notification as FilamentNotification;

class SendFcmNotification extends Page implements HasActions
{
    protected string $view = 'filament.pages.send-fcm-notification';

    public ?array $data = [];
    
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bell';
    
    protected static string|\UnitEnum|null $navigationGroup = 'Notifications';
    
    protected static ?int $navigationSort = 1;
    
    // public function mount(): void
    // {
    //     $this->form->fill();
    // }
    
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                    
                Textarea::make('body')
                    ->required()
                    ->rows(4),
                    
                Select::make('target_type')
                    ->options([
                        'all_users' => 'All Users',
                        'specific_user' => 'Specific User',
                        'topic' => 'Topic',
                    ])
                    ->required()
                    ->live(),
                    
                Select::make('target_value')
                    ->label('User')
                    ->visible(fn ($get) => $get('target_type') === 'specific_user')
                    ->required(fn ($get) => $get('target_type') === 'specific_user')
                    ->searchable()
                    ->options(User::all()->pluck('name', 'id')->toArray())
                    ->getSearchResultsUsing(fn (string $search): array => User::where('name', 'like', "%{$search}%")
                        ->limit(50)
                        ->pluck('name', 'id')
                        ->toArray()),
                        
                TextInput::make('target_value')
                    ->label('Topic Name')
                    ->visible(fn ($get) => $get('target_type') === 'topic')
                    ->required(fn ($get) => $get('target_type') === 'topic'),
                    
                // KeyValue::make('data')
                //     ->keyLabel('Key')
                //     ->valueLabel('Value'),
            ])
            ->statePath('data');
    }
    
    public function send(): void
    {
        $data = $this->form->getState();
        
        // Save to database
        $notification = Notification::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'target_type' => $data['target_type'],
            'target_value' => $data['target_type'] !== 'all_users' ? $data['target_value'] : null,
            // 'data' => $data['data'] ?? [],
            'status' => 'pending',
        ]);
        $notificationMessage = [
            'title' => $data['title'],
            'body' => $data['body'],
        ];
        $service = new FirebaseMessagingService();
        $messaging = app('firebase.messaging');
        $message = CloudMessage::withTarget('token', 'dBalU-zATJupIN8Tn85gLn:APA91bFLchTej3gB33Z5vnaTtbA04hr_iJFaMpYx7Fze9b2Kbkqf3w9FBfhPrHVgASRTthvtBwNl7bgsr7TwTwmi7CK2CzIl6WBWb5cpwoLIZaUuu2mGp90')
            ->withNotification($notificationMessage);
        $result = $messaging->send($message);
        if ($result) {
            // Update the notification status
            $notification->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
            
            FilamentNotification::make()
                ->title('Notification sent successfully!')
                ->success()
                ->send();
        } else {
            FilamentNotification::make()
                ->title('Failed to send notification')
                ->danger()
                ->send();
        }
    }
  
    
    public function sendNotification(): array
    {
        $this->form->getState();
        $this->send();
        
        return $this->form->getState();
    }
}

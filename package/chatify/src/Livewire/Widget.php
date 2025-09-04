<?php

namespace Monzer\FilamentChatifyIntegration\Livewire;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Widget extends Component
{
    public string $chat_url;
    public int $messages_count = 0;

    public function mount()
    {
        $this->chat_url = route('chatify');
        $this->updateMessagesCount();
    }

    public function updateMessagesCount()
    {
        if ($id = auth()->id()) {
            $this->messages_count = DB::table('ch_messages')
                ->where('to_id', $id)
                ->where('seen', 0)
                ->count();
        }
    }

    public function render()
    {
        if (!auth()->id()) {
            return Blade::render('<div></div>');
        }

        return view('filament-chatify-integration::livewire.widget');
    }
}

<x-filament-panels::page>
    {{$this->form}}
    <button type="submit" wire:click="sendNotification">
            Submit
        </button>
    <x-filament-actions::modals />
</x-filament-panels::page>

@php
    $plugin = filament()->getPlugin('chatify-integration');
    $page = $plugin->getCustomPage();
    $is_floating_chat_widget_disabled = $plugin->isFloatingChatWidgetDisabled();
    $chat_url = app()->make($page)::getUrl() ?? route('chatify');
    $direction = app()->getLocale() === 'ar' || app()->getLocale() === 'he' || app()->getLocale() === 'fa' ? 'rtl' : 'ltr';
    $isRtl = $direction === 'rtl';
@endphp
<div class="{{ $isRtl ? 'rtl' : 'ltr' }}" dir="{{ $direction }}">
    @if(! $is_floating_chat_widget_disabled)
        <div wire:poll.30s="updateMessagesCount" class="{{ $isRtl ? 'ml-auto' : 'mr-auto' }}">
            <button class="chat-button {{ $isRtl ? 'float-left' : 'float-right' }}" onclick="window.open('{{ $chat_url }}', '_blank')">
                <svg class="chat-icon" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none">
                    <path
                            d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z"/>
                </svg>
                @if($messages_count > 0)
                    <div class="message-badge {{ $isRtl ? 'left-0' : 'right-0' }}" id="message-count">{{ $messages_count }}</div>
                @endif
            </button>
        </div>
    @endif
</div>

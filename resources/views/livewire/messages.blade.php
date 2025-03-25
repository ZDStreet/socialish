<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $conversations = [];

    public function mount()
    {
        // Fetch conversations for the authenticated user
        $this->conversations = auth()->user()->conversations()->with('recipient')->get();
    }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="p-4">
        <flux:heading>{{ __('Messages') }}</flux:heading>
        <flux:subheading>{{ __('View and manage your conversations') }}</flux:subheading>
    </div>
    
    <div class="grid gap-4 md:grid-cols-3">
        <div class="md:col-span-1">
            <div class="relative h-[calc(100vh-12rem)] overflow-hidden rounded-xl border border-neutral-200 p-4 dark:border-neutral-700">
                <flux:heading class="text-sm">{{ __('Conversations') }}</flux:heading>
                <div class="mt-4">
                    @forelse ($conversations as $conversation)
                        <div class="flex items-center p-2 border-b border-neutral-200 dark:border-neutral-700">
                            <div class="relative">
                                @if ($conversation->recipient->avatar)
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-white dark:border-gray-700 shadow-md" 
                                         src="{{ Storage::url($conversation->recipient->avatar) }}" 
                                         alt="{{ $conversation->recipient->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center border-2 border-white dark:border-gray-700 shadow-md">
                                        <span class="text-sm font-bold text-white">{{ $conversation->recipient->initials() }}</span>
                                    </div>
                                @endif
                                <span class="absolute top-0 right-0 h-3 w-3 rounded-full bg-green-500 border-2 border-white dark:border-neutral-800"></span>
                            </div>
                            <a href="#" class="ml-4 text-blue-500">
                                {{ $conversation->recipient->name }}
                            </a>
                        </div>
                    @empty
                        <p>{{ __('No conversations found.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="md:col-span-2">
            <div class="relative h-[calc(100vh-12rem)] overflow-hidden rounded-xl border border-neutral-200 p-4 dark:border-neutral-700">
                <flux:heading class="text-sm">{{ __('Message Content') }}</flux:heading>
                <div class="mt-4">
                    <x-placeholder-pattern class="h-full w-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                </div>
            </div>
        </div>
    </div>
</div>

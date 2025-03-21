<x-layouts.app :title="__('Messages')">
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
                        <x-placeholder-pattern class="h-full w-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
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
</x-layouts.app>

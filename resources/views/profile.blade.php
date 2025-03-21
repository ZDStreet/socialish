<x-layouts.app :title="__('Profile')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="p-4">
            <flux:heading>{{ __('Profile') }}</flux:heading>
            <flux:subheading>{{ __('View and manage your profile information') }}</flux:subheading>
        </div>
        
        <div class="grid gap-4">
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-4">
                        <div class="relative flex h-20 w-20 shrink-0 overflow-hidden rounded-lg">
                            <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-xl font-medium text-black dark:bg-neutral-700 dark:text-white">
                                {{ auth()->user()->initials() }}
                            </span>
                        </div>
                        <div>
                            <flux:heading size="lg">{{ auth()->user()->name }}</flux:heading>
                            <flux:text>{{ auth()->user()->email }}</flux:text>
                        </div>
                    </div>
                    
                    <x-placeholder-pattern class="h-40 w-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

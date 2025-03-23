<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

    <!-- Push content down to accommodate the fixed search bar -->
        <div class="pt-14"></div>
        
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <flux:heading>{{ ('Socialish') }}</flux:heading>
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="('Platform')" class="grid">
                    <flux:navlist.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Feed') }}</flux:navlist.item>
                    <flux:navlist.item icon="user" :href="route('profile')" :current="request()->routeIs('profile')" wire:navigate>{{ __('Profile') }}</flux:navlist.item>
                    <flux:navlist.item icon="magnifying-glass" :href="route('users.search')" :current="request()->routeIs('users.search')" wire:navigate>{{ __('Users') }}</flux:navlist.item>
                    <flux:navlist.item icon="message-square" :href="route('messages')" :current="request()->routeIs('messages')" wire:navigate>{{ __('Messages') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Profile') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts

        <!-- Global Search Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('global-search');
                const searchResults = document.getElementById('search-results');
                const searchLoading = document.getElementById('search-loading');
                const searchEmpty = document.getElementById('search-empty');
                const searchContent = document.getElementById('search-content');
                let searchTimeout;

                searchInput.addEventListener('focus', function() {
                    if (searchInput.value.trim().length > 0) {
                        searchResults.classList.remove('hidden');
                    }
                });

                searchInput.addEventListener('blur', function(e) {
                    // Delay hiding to allow clicks on results
                    setTimeout(() => {
                        searchResults.classList.add('hidden');
                    }, 200);
                });

                searchInput.addEventListener('input', function() {
                    const query = searchInput.value.trim();
                    
                    // Clear previous timeout
                    clearTimeout(searchTimeout);
                    
                    if (query.length < 2) {
                        searchResults.classList.add('hidden');
                        return;
                    }

                    // Show the results container
                    searchResults.classList.remove('hidden');
                    
                    // Show loading state
                    searchLoading.classList.remove('hidden');
                    searchEmpty.classList.add('hidden');
                    searchContent.innerHTML = '';
                    
                    // Debounce search requests
                    searchTimeout = setTimeout(() => {
                        // Fetch search results
                        fetch(`/api/users/search?q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                searchLoading.classList.add('hidden');
                                
                                if (data.length === 0) {
                                    searchEmpty.classList.remove('hidden');
                                    return;
                                }
                                
                                // Clear and populate results
                                searchContent.innerHTML = '';
                                
                                data.forEach(user => {
                                    const userElement = document.createElement('a');
                                    userElement.href = `/users/${user.id}`;
                                    userElement.className = 'flex items-center gap-3 px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700';
                                    
                                    const avatar = document.createElement('div');
                                    avatar.className = 'flex h-10 w-10 items-center justify-center rounded-full bg-zinc-200 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-200';
                                    avatar.textContent = user.name.charAt(0).toUpperCase();
                                    
                                    const details = document.createElement('div');
                                    details.className = 'flex flex-col';
                                    
                                    const name = document.createElement('div');
                                    name.className = 'text-sm font-medium text-zinc-900 dark:text-zinc-100';
                                    name.textContent = user.name;
                                    
                                    const username = document.createElement('div');
                                    username.className = 'text-xs text-zinc-500 dark:text-zinc-400';
                                    username.textContent = user.email || '';
                                    
                                    details.appendChild(name);
                                    details.appendChild(username);
                                    
                                    userElement.appendChild(avatar);
                                    userElement.appendChild(details);
                                    
                                    searchContent.appendChild(userElement);
                                });
                            })
                            .catch(error => {
                                console.error('Search error:', error);
                                searchLoading.classList.add('hidden');
                                searchEmpty.classList.remove('hidden');
                            });
                    }, 300);
                });

                // Close search results when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                        searchResults.classList.add('hidden');
                    }
                });
            });
        </script>
    </body>
</html>

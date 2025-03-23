<x-layouts.app :title="__('Search Users')">
    <div class="py-8 px-4 sm:px-0">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    @volt('user-search')
                        <div class="flex flex-col gap-6">
                            <x-auth-header :title="__('Search Users')" :description="__('Find people to connect with')" />

                            <div class="mb-6">
                                <flux:input
                                    wire:model.live.debounce.300ms="searchTerm"
                                    type="search"
                                    placeholder="Search by name or username..."
                                    class="w-full"
                                />
                            </div>
                            
                            <div class="space-y-4">
                                @if($searchTerm && strlen($searchTerm) >= 2)
                                    @if($users && count($users) > 0)
                                        @foreach($users as $user)
                                            <div class="flex items-center gap-4 p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                                <div class="flex-shrink-0">
                                                    @if($user->avatar)
                                                        <img class="h-14 w-14 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-700" 
                                                            src="{{ Storage::url($user->avatar) }}" 
                                                            alt="{{ $user->name }}">
                                                    @else
                                                        <div class="h-14 w-14 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center ring-2 ring-gray-200 dark:ring-gray-600">
                                                            <span class="text-xl font-bold text-gray-600 dark:text-gray-300">{{ $user->initials() }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $user->name }}
                                                    </h3>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">
                                                        {{ $user->bio ? Str::limit($user->bio, 100) : 'No bio available' }}
                                                    </p>
                                                </div>
                                                <a href="{{ route('profile', $user->username) }}" class="flex-shrink-0">
                                                    <flux:button size="sm" variant="outline">
                                                        View Profile
                                                    </flux:button>
                                                </a>
                                            </div>
                                        @endforeach
                                        
                                        @if($users->hasMorePages())
                                            <div class="flex justify-center mt-6">
                                                <flux:button wire:click="loadMore" variant="secondary" class="text-sm">
                                                    {{ __('Load More') }}
                                                </flux:button>
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-center py-8">
                                            <p class="text-gray-500 dark:text-gray-400">
                                                {{ __('No users found matching ":search"', ['search' => $searchTerm]) }}
                                            </p>
                                        </div>
                                    @endif
                                @elseif($searchTerm)
                                    <div class="text-center py-8">
                                        <p class="text-gray-500 dark:text-gray-400">
                                            {{ __('Please enter at least 2 characters to search') }}
                                        </p>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <p class="text-gray-500 dark:text-gray-400">
                                            {{ __('Start typing to search for users') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endvolt
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Volt\Component;

new class extends Component {
    use WithPagination;
    
    /**
     * The search term.
     */
    #[Url(history: true)]
    public string $searchTerm = '';
    
    /**
     * The number of users to show per page.
     */
    public int $perPage = 10;
    
    /**
     * Get the users that match the search term.
     */
    public function with(): array
    {
        return [
            'users' => $this->users(),
        ];
    }
    
    /**
     * Get the users that match the search term.
     */
    public function users()
    {
        if (strlen($this->searchTerm) < 2) {
            return collect([]);// Return empty collection instead of null
        }
        
        return User::where('id', '!=', Auth::id())
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('name')
            ->paginate($this->perPage);
    }
    
    /**
     * Load more users.
     */
    public function loadMore()
    {
        $this->perPage += 10;
    }
    
    /**
     * Reset pagination when search changes.
     */
    public function updatedSearchTerm()
    {
        $this->resetPage();
        $this->perPage = 10;
    }
}; 
?>

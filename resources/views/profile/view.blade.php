<?php

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;

new class extends Component {
    public ?User $user = null;
    public bool $isOwnProfile = false;
    public $posts = [];

    public function mount($username = null): void
    {
        if ($username) {
            $this->user = User::where('username', $username)->firstOrFail();
        } else {
            $this->user = Auth::user();
        }

        $this->isOwnProfile = Auth::id() === $this->user->id;
    }

    public function startConversation(): void
    {
        $recipientId = $this->user->id;

        // Check if a conversation already exists
        $conversation = Conversation::where(function ($query) use ($recipientId) {
            $query->where('user_id', Auth::id())
                  ->where('recipient_id', $recipientId);
        })->orWhere(function ($query) use ($recipientId) {
            $query->where('user_id', $recipientId)
                  ->where('recipient_id', Auth::id());
        })->first();

        if (!$conversation) {
            // Create a new conversation if it doesn't exist
            Conversation::create([
                'user_id' => Auth::id(),
                'recipient_id' => $recipientId,
            ]);
        }

        session()->flash('success', __('Conversation started!'));
        $this->redirect(route('messages'));
    }
}; 
?>

<x-layouts.app :title="isset($user) && $user ? $user->name : __('Profile')">
    <div class="py-8 px-4 sm:px-0">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    @volt('profile-view')
                        <div class="flex flex-col gap-6">
                            <!-- Profile Header -->
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
                                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                                    <!-- Profile Avatar -->
                                    <div class="relative">
                                        @if($user->avatar)
                                            <img class="h-28 w-28 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-md" 
                                                 src="{{ Storage::url($user->avatar) }}" 
                                                 alt="{{ $user->name }}">
                                        @else
                                            <div class="h-28 w-28 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center border-4 border-white dark:border-gray-700 shadow-md">
                                                <span class="text-3xl font-bold text-white">{{ $user->initials() }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Profile Info -->
                                    <div class="text-center sm:text-left">
                                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Joined {{ $user->created_at->diffForHumans() }}</p>
                                        
                                        @if($user->bio)
                                            <div class="mt-3 max-w-xl text-gray-600 dark:text-gray-300 text-sm sm:text-base">
                                                {{ $user->bio }}
                                            </div>
                                        @else
                                            <p class="mt-3 text-gray-500 dark:text-gray-500 italic text-sm">{{ __('No bio provided') }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Edit Profile Button (if viewing own profile) -->
                                @if($isOwnProfile)
                                    <a href="{{ route('profile.edit') }}" 
                                       class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 
                                              border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm 
                                              text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 
                                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 
                                              dark:focus:ring-offset-gray-800 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        {{ __('Edit Profile') }}
                                    </a>
                                @else
                                    <button wire:click="startConversation" 
                                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 
                                                   border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm 
                                                   text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 
                                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 
                                                   dark:focus:ring-offset-gray-800 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17 8a3 3 0 11-6 0 3 3 0 016 0zM4 8a3 3 0 116 0 3 3 0 01-6 0zM12 14a6 6 0 00-8.485 0A1 1 0 004 16h12a1 1 0 00.485-2z" />
                                        </svg>
                                        {{ __('Start Conversation') }}
                                    </button>
                                @endif
                            </div>
                            
                            <!-- Stats Summary -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 my-2 border-y border-gray-200 dark:border-gray-700 py-4">
                                <div class="text-center">
                                    <span class="block text-2xl font-bold text-gray-900 dark:text-white">{{ isset($posts) ? count($posts) : 0 }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Posts</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-2xl font-bold text-gray-900 dark:text-white">0</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Followers</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-2xl font-bold text-gray-900 dark:text-white">0</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Following</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-2xl font-bold text-gray-900 dark:text-white">0</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Likes</span>
                                </div>
                            </div>
                            
                            <!-- Profile Content Section -->
                            <div class="mt-2">
                                <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                                    {{ __('Recent Posts') }}
                                </h2>
                                
                               
                                <div class="bg-gray-50 dark:bg-gray-750 rounded-xl p-8 text-center">
                                    <p class="mt-4 text-gray-500 dark:text-gray-400 font-medium">{{ __('No posts yet.') }}</p>
                                    @if($isOwnProfile)
                                        <p class="mt-2 text-gray-500 dark:text-gray-400 text-sm">{{ __('Your posts will appear here.') }}</p>
                                        <a href="#" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            {{ __('Create First Post') }}                                            </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endvolt
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
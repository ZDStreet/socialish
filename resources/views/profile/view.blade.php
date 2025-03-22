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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Recent Posts') }}
                                </h2>
                                
                                @if(isset($posts) && count($posts) > 0)
                                    <div class="space-y-6">
                                        @foreach($posts as $post)
                                            <div class="bg-gray-50 dark:bg-gray-750 border border-gray-200 dark:border-gray-700 rounded-xl p-4 sm:p-6 hover:shadow-md transition-shadow">
                                                <div class="flex items-start gap-3 mb-3">
                                                    <div class="flex-shrink-0">
                                                        @if($user->avatar)
                                                            <img class="h-8 w-8 rounded-full" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                                                        @else
                                                            <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                                <span class="text-sm font-bold text-gray-600 dark:text-gray-300">{{ $user->initials() }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="prose prose-sm dark:prose-invert max-w-none">
                                                    <p>{{ $post->content }}</p>
                                                </div>
                                                
                                                <div class="mt-4 flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                                    <button class="flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                        </svg>
                                                        Like
                                                    </button>
                                                    <button class="flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03 8 9-8s9 3.582 9 8z" />
                                                        </svg>
                                                        Comment
                                                    </button>
                                                    <button class="flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                                        </svg>
                                                        Share
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bg-gray-50 dark:bg-gray-750 rounded-xl p-8 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <p class="mt-4 text-gray-500 dark:text-gray-400 font-medium">{{ __('No posts yet.') }}</p>
                                        @if($isOwnProfile)
                                            <p class="mt-2 text-gray-500 dark:text-gray-400 text-sm">{{ __('Your posts will appear here.') }}</p>
                                            <a href="#" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                {{ __('Create First Post') }}
                                            </a>
                                        @endif
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

use App\Models\User;
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
}; 
?>

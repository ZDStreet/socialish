<x-layouts.app :title="__('Edit Profile')">
    <div class="py-8 px-4 sm:px-0">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    @volt('profile-edit')
                        <div class="flex flex-col gap-6">
                            <x-auth-header :title="__('Edit Profile')" :description="__('Update your profile information below')" />

                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form wire:submit="updateProfile" class="flex flex-col gap-6">
                                <!-- Avatar -->
                                <div class="mb-2">
                                    <flux:label for="avatar" :value="__('Profile Picture')" class="mb-3 block" />
                                    
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                        <div class="relative group">
                                            @if(Auth::user()->avatar)
                                                <img class="h-24 w-24 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-700" 
                                                     src="{{ Storage::url(Auth::user()->avatar) }}" 
                                                     alt="{{ Auth::user()->name }}">
                                            @else
                                                <div class="h-24 w-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center ring-2 ring-gray-200 dark:ring-gray-600">
                                                    <span class="text-2xl font-bold text-gray-600 dark:text-gray-300">{{ Auth::user()->initials() }}</span>
                                                </div>
                                            @endif
                                            
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-full transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                <span class="text-white text-xs font-medium px-2 py-1 bg-black bg-opacity-50 rounded-md">Change</span>
                                            </div>
                                        </div>
                                        
                                        <div class="w-full sm:max-w-md">
                                            <flux:input
                                                wire:model="avatar"
                                                type="file"
                                                accept="image/*"
                                                class="block w-full"
                                            />
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Recommended size: 400x400px. Max size: 1MB</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Show preview of uploaded image -->
                                    @if($avatar)
                                        <div class="mt-4 flex items-center gap-3">
                                            <div>
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('New profile picture:') }}</p>
                                                <img src="{{ $avatar->temporaryUrl() }}" class="mt-2 h-16 w-16 rounded-full object-cover ring-2 ring-indigo-500" alt="Preview">
                                            </div>
                                            <button type="button" wire:click="$set('avatar', null)" class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                Cancel
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                    <!-- Name -->
                                    <flux:input
                                        wire:model="name"
                                        :label="__('Name')"
                                        type="text"
                                        required
                                        autofocus
                                        placeholder="Your full name"
                                        class="mb-4"
                                    />

                                    <!-- Bio -->
                                    <flux:textarea
                                        wire:model="bio"
                                        :label="__('Bio')"
                                        rows="4"
                                        placeholder="Tell us a bit about yourself..."
                                    />
                                </div>

                                <div class="flex items-center justify-end pt-2">
                                    <flux:button variant="primary" type="submit" class="min-w-[120px]">
                                        {{ __('Save Changes') }}
                                    </flux:button>
                                </div>
                            </form>
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
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Volt\Component;

new class extends Component {
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:500')]
    public ?string $bio = '';

    #[Validate('nullable|image|max:1024')]
    public $avatar = null;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->bio = $user->bio ?? '';
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(): void
    {
        $this->validate();

        $user = Auth::user();
        
        $userData = [
            'name' => $this->name,
            'bio' => $this->bio,
        ];

        // Upload avatar if provided
        if ($this->avatar) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $this->avatar->store('avatars', 'public');
            $userData['avatar'] = $avatarPath;
        }

        $user->update($userData);
        
        $this->reset('avatar');
        session()->flash('status', __('Profile updated successfully!'));
    }
}; 
?>

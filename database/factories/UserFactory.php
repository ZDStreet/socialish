<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'bio' => fake()->optional(0.8)->paragraph(),
            'avatar' => $this->storeRandomAvatar(30),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Store a random avatar in storage with a given probability.
     *
     * @param int $probability Percentage chance of saving an avatar
     * @return string|null
     */
    private function storeRandomAvatar(int $probability): ?string
    {
        if (rand(1, 100) <= $probability) {
            try {
                $avatarContent = file_get_contents('https://i.pravatar.cc/150?u=' . uniqid());
                if ($avatarContent === false) {
                    \Log::error('Failed to fetch avatar content.');
                    return null;
                }

                $avatarPath = 'avatars/' . uniqid() . '.jpg';
                Storage::disk('local')->put($avatarPath, $avatarContent);

                if (!Storage::disk('local')->exists($avatarPath)) {
                    \Log::error('Failed to save avatar to storage.');
                    return null;
                }

                return $avatarPath;
            } catch (\Exception $e) {
                \Log::error('Failed to save avatar: ' . $e->getMessage());
                return null;
            }
        }

        return null;
    }
}

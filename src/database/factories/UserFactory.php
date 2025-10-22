<?php

namespace Database\Factories;

use App\Enums\FrontPageViewingMode;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $name = fake()->name();

        return [
            'name' => $name,
            'is_admin' => false,
            'handle' => Str::slug($name),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'viewing_mode' => FrontPageViewingMode::Private,
            'has_email_authentication' => false,
        ];
    }

    /**
     * Adds notes to the user
     *
     * @param  int|null  $count  if not present, a random number will be chosen between 0 and 10
     */
    public function addNotes(?int $count = null): static
    {
        return $this->afterCreating(
            fn (User $user) => Note::factory()
                ->count($count ?? rand(0, 10))
                ->for($user)
                ->create()
        );
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
}

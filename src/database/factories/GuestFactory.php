<?php

namespace Database\Factories;

use App\Enums\FrontPageViewingMode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class GuestFactory extends Factory
{
    /**
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => __('Guest User'),
            'is_admin' => false,
            'handle' => 'guest-user',
            'email' => 'guest@nowhere.local',
            'email_verified_at' => null,
            'password' => null,
            'remember_token' => null,
            'avatar_url' => User::guest_avatar_url,
            'viewing_mode' => FrontPageViewingMode::Guest,
        ];
    }
}

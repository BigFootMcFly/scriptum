<?php

namespace Database\Factories;

use App\Enums\NoteVisibility;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->realText(100);
        $body = fake()->realText();
        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'body' => $body,
            'body_content' => Str::limit($body, 20), //TODO: add filter to this
            'visibility' => fake()->randomElement(NoteVisibility::class),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\NoteVisibility;
use App\Models\Note;
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
        // TODO: make a TipTap JSON faker
        $body = '{"type":"doc","content":[{"type":"paragraph","content":[{"type":"text","marks":[{"type":"bold"}],"text":"Or"},{"type":"text","text":" is "},{"type":"text","marks":[{"type":"italic"}],"text":"it"},{"type":"text","text":"?"}],"attrs":{"textAlign":"start"}},{"type":"paragraph","attrs":{"textAlign":"start"},"content":[{"type":"text","marks":[{"type":"link","attrs":{"href":"https:\/\/www.google.com\/search?udm=14&q=is+there+a+cow+level%3F","target":"_blank","rel":"noopener noreferrer nofollow","class":null}}],"text":"maybe..."}]},{"type":"paragraph","attrs":{"textAlign":"start"},"content":[{"type":"text","text":"..."}]}]}';

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'body' => $body,
            'body_content' => Note::extractBodyContents(json_decode($body, true)),
            'visibility' => fake()->randomElement(NoteVisibility::class),
        ];
    }
}

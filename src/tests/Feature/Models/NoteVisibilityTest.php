<?php

use App\Enums\NoteVisibility;
use App\Models\Note;
use App\Models\User;

it('shows public Notes to anyone', function () {
    $Note = Note::factory()->create(['visibility' => NoteVisibility::Public]);

    $result = Note::query()->frontPage()->get();

    expect($result)->toHaveCount(1);
    expect($result->first()->id)->toBe($Note->id);
});

it('does not show private Notes to guests', function () {
    Note::factory()->create(['visibility' => NoteVisibility::Private]);

    $result = Note::query()->frontPage()->get();

    expect($result)->toBeEmpty();
});

it('shows private Notes to the owner', function () {
    $user = User::factory()->create();
    $Note = Note::factory()->create([
        'visibility' => NoteVisibility::Private,
        'user_id' => $user->id,
    ]);

    $result = Note::query()->frontPage($user)->get();

    expect($result)->toHaveCount(1);
    expect($result->first()->id)->toBe($Note->id);
});

it('does not show other users private Notes', function () {
    $user = User::factory()->create();
    $note = Note::factory()->create(['visibility' => NoteVisibility::Private]);

    $result = Note::query()->frontPage($user)->get();

    expect($result)->toBeEmpty();
});

it('does not show soft deleted posts', function () {
    $user = User::factory()->create();
    $note = Note::factory()->create([
        'visibility' => 'public',
        'user_id' => $user->id,
        'deleted_at' => now(),
    ]);

    $result = Note::query()->frontPage($user)->get();

    expect($result)->toBeEmpty();
});

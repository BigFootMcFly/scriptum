<?php

use App\Enums\NoteVisibility;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

it('allows viewing public posts for guests', function (): void {
    $note = Note::factory()->create(['visibility' => NoteVisibility::Public]);

    expect(auth()->user())->toBeNull();

    $can = Gate::forUser(null)->allows('view', $note);

    expect($can)->toBeTrue();

});

it('denies viewing private posts to other users', function (): void {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    $note = Note::factory()->create([
        'visibility' => NoteVisibility::Private,
        'user_id' => $owner->id,
    ]);

    $can = Gate::forUser($other)->allows('view', $note);

    expect($can)->toBeFalse();
});

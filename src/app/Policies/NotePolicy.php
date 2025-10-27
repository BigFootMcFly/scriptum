<?php

namespace App\Policies;

use App\Enums\NoteVisibility;
use App\Models\Note;
use App\Models\User;

class NotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (auth()->check()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Note $note): bool
    {

        // admins can view any notes
        if ($user?->isAdmin()) {
            return true;
        }

        return match ($note->visibility) {
            // Public: visible to anyone
            NoteVisibility::Public => true,
            // Private: only the owner
            NoteVisibility::Private => $user && $user->id === $note->user_id,
            // Hidden: currently reserved for maintanance reasons
            NoteVisibility::Hidden => false,
            // Restricted: needs admin intervention
            NoteVisibility::Restricted => false,
            default => false,
        };

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return ! $user->isGuest() && $user->isVerified();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Note $note): bool
    {

        if ($user->isAdmin()) {
            return true;
        }

        if (in_array($note->visibility, [NoteVisibility::Restricted, NoteVisibility::Hidden])) {
            return false;
        }

        if ($user->id === $note->user_id && $user->isVerified()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Note $note): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (in_array($note->visibility, [NoteVisibility::Restricted, NoteVisibility::Hidden])) {
            return false;
        }

        if ($user->id === $note->user_id && $user->isVerified()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the models can be bulk deleted.
     */
    public function deleteAny(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Note $note): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (in_array($note->visibility, [NoteVisibility::Restricted, NoteVisibility::Hidden])) {
            return false;
        }

        if ($user->id === $note->user_id && $user->isVerified()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can bulk restore the model.
     */
    public function restoreAny(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Note $note): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently bulk delete the model.
     */
    public function forceDeleteAny(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determines if the current user can edit a post listed on the front page
     */
    public function updateOnFrontPage(User $user, Note $note): bool
    {
        return $note->user_id === $user->id && $user->isVerified();
    }
}

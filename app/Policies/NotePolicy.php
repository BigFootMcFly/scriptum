<?php

namespace App\Policies;

use App\Enums\NoteVisibility;
use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // allowing to show up on the nomad panel
        return $user->is_admin;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Note $note): bool
    {
        // admins can view any notes
        if ($user->is_admin) {
            return true;
        }

        // Public is visible to anyone
        if ($note->visibility === NoteVisibility::Public) {
            return true;
        }

        // Private: only the owner
        if ($note->visibility === NoteVisibility::Private) {
            return $user && $user->id === $note->user_id;
        }

        // Restricted: needs admin intervention
        if ($note->visibility === NoteVisibility::Restricted) {
            return false;
        }

        // Hidden: currently reserved for maintanance reasons
        if ($note->visibility === NoteVisibility::Hidden) {
            return false;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return null !== $user;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Note $note): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if (in_array($note->visibility, [NoteVisibility::Restricted, NoteVisibility::Hidden])) {
            return false;
        }

        if ($user->id === $note->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Note $note): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if (in_array($note->visibility, [NoteVisibility::Restricted, NoteVisibility::Hidden])) {
            return false;
        }

        if ($user->id === $note->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the models can be bulk deleted.
     */
    public function deleteAny(User $user): bool
    {
        if ($user->is_admin) {
            return true;
        }

        return false;
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Note $note): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if (in_array($note->visibility, [NoteVisibility::Restricted, NoteVisibility::Hidden])) {
            return false;
        }

        if ($user->id === $note->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can bulk restore the model.
     */
    public function restoreAny(User $user): bool
    {
        if ($user->is_admin) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Note $note): bool
    {
        if ($user->is_admin) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently bulk delete the model.
     */
    public function forceDeleteAny(User $user): bool
    {
        if ($user->is_admin) {
            return true;
        }

        return false;
    }

}

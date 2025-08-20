<?php

namespace App\Models;

use App\Enums\NoteVisibility;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    /** @use HasFactory<\Database\Factories\NoteFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'visibility',
        'title',
        'slug',
        'body',
    ];

    protected function casts(): array {
        return [
            'visibility' => NoteVisibility::class,
            'body' => 'array' // tiptap's JSON format
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => explode("/", $value)[1] ?? $value,
            set: fn (string $value) => ($this->user->handle/* ?? 'anonymous'*/) . "/$value",
        );
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $this->user->handle.'/'.$this->slug,
        );
    }

    public function scopeVisibleTo(Builder $query, ?Model $user = null): Builder
    {
        return $query->where(function ($q) use ($user) {
            // Public is always visible
            $q->where('visibility', NoteVisibility::Public->value);

            if ($user) {
                // Private only to owner
                //NOTE: 'hidden' and 'restricted' are not handled (not shown) here for now, they require admin intervention
                $q->orWhere(function ($q) use ($user) {
                    $q->where('visibility', NoteVisibility::Private->value)
                      ->where('user_id', $user->id);
                });
            }
        });
    }
}

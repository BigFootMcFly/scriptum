<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\FrontPageViewingMode;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Filament\Panel\Concerns\HasAvatars;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasAvatars;
    use Notifiable;

    protected const string default_avatar_url = 'avatars/_default.svg';
    protected const string guest_avatar_url = 'avatars/_guest.svg';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'handle',
        'avatar_url',
        'viewing_mode',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'viewing_mode' => FrontPageViewingMode::class,
        ];
    }

    // relations

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    // accessors

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string => Storage::url(
                match ($value) {
                    null => static::default_avatar_url,
                    default => $value,
                },
            )
        );
    }

    // utilities

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }


    public function isGuest(): bool
    {
        return null === $this->id;
    }

    public function hasAvatar(): bool
    {
        if ($this->isGuest()) {
            return false;
        }

        return Arr::get($this->attributes, 'avatar_url') !== null;
        //return $this->getRawOriginal('avatar_url') !== null;
        //return ($this->attributes['avatar_url'] ?? null) !== null;
    }

    // ----------------------------------------------------------------------------------------------------------------
    protected function permalink(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string =>
                route('view-user-notes', ['user' => $this->handle])
        );
    }


    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * returns if the user can access the filament panel
     *
     * @param Panel $panel
     *
     * @return bool
     *
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // only admins can access the nomad panel
        if ($panel->getId() === 'nomad') {
            return $this->isAdmin();

        }

        // other panel(s) (which there are none for now) can be accessed by logged in users
        return true;
    }

    public static function guestUser(): self
    {
        return static::make([
            'name' => __('Guest User'),
            'avatar_url' => static::guest_avatar_url
        ]);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');
        return $this->$avatarColumn ? Storage::url($this->$avatarColumn) : null;
    }


}

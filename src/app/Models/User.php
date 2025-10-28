<?php

namespace App\Models;

use App\Enums\FrontPageViewingMode;
use App\Traits\AssureNotNull;
use DateTime;
use Exception;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\Email\Contracts\HasEmailAuthentication;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Filament\Panel\Concerns\HasAvatars;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property array<string>|null $app_authentication_recovery_codes
 * @property string $handle
 * @property string $name
 * @property bool $is_admin
 * @property DateTime|null $email_verified_at
 * @property bool $has_email_authentication
 * @property string|null $app_authentication_secret
 * @property string $email
 * @property FrontPageViewingMode $viewing_mode
 */
class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, HasEmailAuthentication, MustVerifyEmail
{
    use AssureNotNull;
    use HasAvatars;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use Notifiable;

    protected const string default_avatar_url = 'avatars/_default.svg';

    public const string guest_avatar_url = 'avatars/_guest.svg';

    private const string guest_user_handle = 'guest-user';

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
        'app_authentication_secret',
        'app_authentication_recovery_codes',
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
            'has_email_authentication' => 'boolean',
            'app_authentication_secret' => 'encrypted',
            'app_authentication_recovery_codes' => 'encrypted:array',
        ];
    }

    // relations

    /**
     * @return HasMany<Note, $this>
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    // accessors

    /**
     * @return Attribute<string, string>
     */
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
        return $this->handle === self::guest_user_handle;
    }

    public function hasAvatar(): bool
    {
        if ($this->isGuest()) {
            return false;
        }

        return Arr::get($this->attributes, 'avatar_url') !== null;
        // return $this->getRawOriginal('avatar_url') !== null;
        // return ($this->attributes['avatar_url'] ?? null) !== null;
    }

    // ----------------------------------------------------------------------------------------------------------------
    /**
     * @return Attribute<string, string>
     */
    protected function permalink(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string => route('view-user-notes', ['user' => $this->handle])
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
        // return new GuestFactory()->make();
        return self::make([
            'name' => __('Guest User'),
            'is_admin' => false,                                // non fillable
            'handle' => self::guest_user_handle,
            'email' => 'guest@nowhere.local',
            'email_verified_at' => null,                        // non fillable
            'password' => null,
            'remember_token' => null,                           // non fillable
            'avatar_url' => User::guest_avatar_url,
            'viewing_mode' => FrontPageViewingMode::Guest,
        ]);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');

        return $this->$avatarColumn ? Storage::url($this->$avatarColumn) : null;
    }

    // Multi Factor Authentication

    // - MFA email authentication

    public function hasEmailAuthentication(): bool
    {
        return $this->has_email_authentication;
    }

    public function toggleEmailAuthentication(bool $condition): void
    {
        $this->has_email_authentication = $condition;
        $this->save();
    }

    // - MFA app authentication

    public function getAppAuthenticationSecret(): ?string
    {
        return $this->app_authentication_secret;
    }

    public function saveAppAuthenticationSecret(?string $secret): void
    {
        $this->app_authentication_secret = $secret;
        $this->save();
    }

    public function getAppAuthenticationHolderName(): string
    {
        return $this->email;
    }

    // - MFA app authentication recovery

    /**
     * @return ?array<string>
     */
    public function getAppAuthenticationRecoveryCodes(): ?array
    {
        return $this->app_authentication_recovery_codes;
    }

    /**
     * @param  array<string> | null  $codes
     */
    public function saveAppAuthenticationRecoveryCodes(?array $codes): void
    {
        $this->app_authentication_recovery_codes = $codes;
        $this->save();
    }

    // ----------------------------------------------------------------------------------------------------------------
    public static function assure(?User $user = null): User
    {
        if ( ! $user instanceof User ) {
            $user = auth()->user();
        }

        if ( ! $user instanceof User ) {
            throw new Exception('Cannot assure User', 500);
        }

        return $user;
    }
}

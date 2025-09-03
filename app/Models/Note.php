<?php

namespace App\Models;

use App\Enums\NoteVisibility;
use App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\CodeBlock;
use App\Helpers\TipTap\TipTapJsonContentExtractor;
use Filament\Forms\Components\RichEditor\Models\Concerns\InteractsWithRichContent;
use Filament\Forms\Components\RichEditor\Models\Contracts\HasRichContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Note extends Model implements HasRichContent
{
    use InteractsWithRichContent;

    /** @use HasFactory<\Database\Factories\NoteFactory> */
    use HasFactory;

    use SoftDeletes;

    // Configuration

    // -----------------------------------------------------------------------------------------------------------------
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'visibility',
        'title',
        'slug',
        'body',
    ];

    // ----------------------------------------------------------------------------------------------------------------
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'visibility' => NoteVisibility::class,
            'body' => 'array' // tiptap's JSON format
        ];
    }

    // Relations

    // ----------------------------------------------------------------------------------------------------------------
    /**
     * The User the Note belonsg to
     *
     * @return BelongsTo
     *
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Mutators and Accessors

    // ----------------------------------------------------------------------------------------------------------------
    /**
     * Mutator and Accessor for the slug
     *
     * @return Attribute
     *
     * NOTE: the slug must be uniqe for the User, but multiple Users can have the same slug value,
     *       so in the database we store it in to "$userhandle/$noteslug" format,
     *       which will propably also be the public address of the note.
     *
     */
    protected function slug(): Attribute
    {
        //$userScope = $this->user?->handle ?? '#';
        return Attribute::make(
            get: fn (?string $value) => static::getScopedSlug($value),
            //set: fn (string $value) => static::globalizeSlug($userScope,$value),
        );
    }

    // ----------------------------------------------------------------------------------------------------------------
    /**
     * Generate the body_content from the body property
     *
     * @return Attribute
     *
     */
    protected function bodyContent(): Attribute
    {
        return Attribute::make(
            set: fn (): string => static::extractBodyContents($this->body),
        );
    }

    // ----------------------------------------------------------------------------------------------------------------
    protected function permalink(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string =>
                match ($this->user) {
                    null => '',
                    default => route('view-note', ['user' => $this->user->handle, 'slug' => $this->slug])
                }
        );
    }

    // Specializations

    // ----------------------------------------------------------------------------------------------------------------
    /**
     * Setup automations for generating the body_content property.
     */
    protected static function booted(): void
    {
        static::creating(function (Note $note) {

            // creating searchable body content
            $note->body_content = static::extractBodyContents($note->body);
            // globalizing slug
            $user = User::find($note->user_id);
            $note->slug = static::globalizeSlug($user->handle, $note->slug);

        });

        static::updating(function (Note $note) {
            $note->body_content = static::extractBodyContents($note->body);
            $user = User::find($note->user_id);
            $note->slug = static::globalizeSlug($user->handle, $note->getAttribute('slug'));
            //TODO: check, if the slug needs to be updated or not...
        });
    }

    // Utilities


    public function isAdminRestricted(): bool
    {
        return match($this->visibility) {
            NoteVisibility::Hidden,
            NoteVisibility::Restricted => true,
            default => false,
        };
    }

    // Slug handling

    // ----------------------------------------------------------------------------------------------------------------
    /**
     * Makes a slug "global" trough the database
     *
     * @param string $scope The that the slug belongs to
     * @param string $slug The scoped slug
     *
     * @return string The "unique" slug trough the database
     *
     * NOTE: this is here for if the format should be changed or extended, that can be in one place
     *
     */
    public static function globalizeSlug(string $scope, string $slug): string
    {
        return "{$scope}/{$slug}";
    }

    // ----------------------------------------------------------------------------------------------------------------
    /**
     * Returns the "local" part of the slug inside a "global" scope
     *
     * @param string $slug The "global" slug
     *
     * @return string The unique slug in the "local" scope
     *
     */
    public static function getScopedSlug(?string $slug = null): ?string
    {
        return explode("/", $slug)[1] ?? $slug;
    }

    // Helper functions

    // ----------------------------------------------------------------------------------------------------------------
    /**
     * Extracts the human readable text from a TipTap rich content (Filament RichEditor)
     *
     * @param array $body The content in TipTap json format, converted to array by the model
     *
     * @return string
     *
     */
    public static function extractBodyContents(string|array $body): string
    {
        if (is_string($body)) {
            $body = json_decode(json: $body, associative: true);
        }
        // get the extracted content
        $content = TipTapJsonContentExtractor::extractContent($body);
        // remove empty spaces from the beginning and end of a strings
        $content = array_map('trim', $content); //NOTE: why is 'trim' the only function that cannot handle an array?
        // replace multiple white space caracters with on space
        $content = preg_replace('/\s+/', ' ',$content);
        // remove left in new line charackters (this is propably unneccessary)
        $content = str_replace("\n", ' ', $content);

        return implode(
            '|',
            $content
        );
    }

    // Scopes

    // ----------------------------------------------------------------------------------------------------------------
    /**
     * Returns the combined list of records that are visible to the current user or guest
     *
     * guest => all "public" notes
     * user => all "public" notes ant its own "private" notes
     *
     */
    public function scopeFrontPage(Builder $query, ?Model $user = null): Builder
    {
        return $query->where(function ($q) use ($user) {
            // Public is always visible
            $q->where('visibility', NoteVisibility::Public->value);

            if ($user) {
                // Private only to owner
                //NOTE: 'hidden' and 'restricted' are not handled (not shown) here for now, they reserved to require admin intervention
                $q->orWhere(function ($q) use ($user) {
                    $q->where('visibility', NoteVisibility::Private->value)
                      ->where('user_id', $user->id);
                });
            }
        });
    }

    // ----------------------------------------------------------------------------------------------------------------
    /**
     * Returns the records that belongs to the provided user
     */
    public function scopeOwned(Builder $query, User $user): Builder
    {
        return $query->where('user_id',$user->id);
    }

    // ----------------------------------------------------------------------------------------------------------------
    /**
     * Full Text Search in the 'body_content' and 'title' fields
     *
     * @param Builder $query The Eloquen Builder instance (auto injected)
     * @param string $term The search string, can have multiple tokens
     * @param bool $prefix If true, each search token can be partial (a '*' will be added at the end of each one, so 'lara*' will match 'laravel')
     *
     * @return Builder
     *
     */
    public function scopeSearch(Builder $query, string $term, bool $prefix = false): Builder
    {
        // Clean up term
        $term = trim($term);
        $term = preg_replace('/[^A-Za-z0-9 \-\*\"]/', '', $term);

        // if partial tokens are required
        if ($prefix) {
            // Split into tokens and add * to each token
            $tokens = preg_split('/\s+/', $term);
            $tokens = array_map(fn($t) => '"'.$t.'"' . '*', $tokens);
            $term = implode(' ', $tokens);
        } else {
            $term = '"' . $term .'"';
        }

        return $query->from('notes as notes')
            ->join('notes_fts', 'notes.id', '=', 'notes_fts.rowid')
            ->whereRaw('notes_fts MATCH ?', [$term])
            ->select(
                'notes.*',
                DB::raw('bm25(notes_fts) as rank'),
                DB::raw("highlight(notes_fts, 0, '<mark>', '</mark>') as highlight_title"),
                DB::raw("highlight(notes_fts, 1, '<mark>', '</mark>') as highlight_body_content"),
            )
            ->orderBy('rank')
            ;
    }

    // Filament helpers

    // ----------------------------------------------------------------------------------------------------------------
    /**
     * Setting up RichEditor properties for filament
     *
     * @return void
     *
     */
    public function setUpRichContent(): void
    {
        $this->registerRichContent('body')
            ->mergeTags([
                'note.title' => $this->title,
                'note.id' => $this->id,
                'note.slug' => $this->slug,
                'note.permalink' => $this->permalink,
            ])
            ->customBlocks([
                CodeBlock::class
            ])
            ->json()
            ->fileAttachmentsVisibility('private')
            ;
    }

}

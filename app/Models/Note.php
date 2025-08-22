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

    /**
     * Setup automations for generating the body_content property.
     */
    protected static function booted(): void
    {
        static::creating(function (Note $note) {
            $note->body_content = static::extractBodyContents($note->body);
        });
        static::updating(function (Note $note) {
            $note->body_content = static::extractBodyContents($note->body);
        });
    }

    /**
     * Extracts the human readable text from a TipTap rich content (Filament RichEditor)
     *
     * @param array $body The content in TipTap json format, converted to array by the model
     *
     * @return string
     *
     */
    public static function extractBodyContents(array $body): string
    {
        // get the extracted content
        $content = TipTapJsonContentExtractor::extractContent($body);
        // remove empty spaces from the beginning and end of a strings
        $content = array_map('trim', $content); //NOTE: why it the only one what cannot handle an array?
        // replace multiple white space caracters with on space
        $content = preg_replace('/\s+/', ' ',$content);
        // remove left in new line charackters (this is propably unneccessary)
        $content = str_replace("\n", ' ', $content);

        return implode(
            '|',
            $content
        );
    }

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
        return Attribute::make(
            get: fn (?string $value) => explode("/", $value)[1] ?? $value,
            set: fn (string $value) => "{$this->user->handle}/$value",
        );
    }

    /**
     * Generate the body_content from the body property
     *
     * @return Attribute
     *
     */
    protected function bodyContent(): Attribute
    {
        return Attribute::make(
            set: fn (array|string $value): string => static::extractBodyContents($this->body),
        );
    }

    /**
     * get the public adddress of the note
     *
     * @return Attribute
     *
     * NOTE: full URL which this inslude FQDN
     *
     */
    protected function permalink(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string =>
                match ($this->user) {
                    null => '',
                    default => sprintf('%s/notes/%s/%s',
                        request()->getHttpHost(),
                        $this->user->handle,
                        $this->slug
                    )
                }
        );
    }

    public function scopeVisibleTo(Builder $query, ?Model $user = null): Builder
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

        // if partial tokens are required
        if ($prefix) {
            // Split into tokens and add * to each token
            $tokens = preg_split('/\s+/', $term);
            $tokens = array_map(fn($t) => $t . '*', $tokens);
            $term = implode(' ', $tokens);
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

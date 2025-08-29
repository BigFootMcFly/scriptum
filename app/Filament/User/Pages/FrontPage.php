<?php

namespace App\Filament\User\Pages;

use App\Enums\NoteVisibility;
use App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\CodeBlock;
use App\Models\Note;
use Exception;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class FrontPage extends Page implements HasForms
{
    use InteractsWithForms;
    use WithPagination;


    //protected static ?string $title = 'Custom Page Title';

    //protected static ?string $navigationLabel = 'Main page';


    //protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $title = 'My Notes';

    protected static ?string $slug = 'main-page';

    protected string $view = 'filament.user.pages.front-page';

    public string $search = '';

    public bool $partial = true;

    public ?array $data = [
        'title' => null,
        'visibility'  => null,
        'slug' => null,
        'body' => [],
    ];
/*
    public ?array $cdata = [
        'title' => null,
        'visibility'  => null,
        'slug' => null,
        'body' => [],
    ];
*/
/*
        'body'  => [
            'type' => 'doc',
            'content' => [
                'type' => 'paragraph',
                'content' => [],
            ],
        ],
        //'body' => json_decode('{"type":"doc","content":[{"type":"paragraph","content":[]}]}'),
        //{"type":"doc","content":[{"type":"paragraph","content":[]}]}
*/


/* BEGIN */
    public ?Note $editingNote = null;

    // ----------------------------------------------------------------------------------------------------------------
    public function openEditModal(Note $note): void
    {
        $this->editingNote = $note;
        $this->form->statePath('data');
        $this->form->fill($note->toArray());
        $this->dispatch('open-modal', id: 'edit-note');
    }


    // ----------------------------------------------------------------------------------------------------------------

    public function openCreateModal(): void
    {
        $this->form->statePath('data');
        $this->data = [
            'body' => [],
            'visibility' => NoteVisibility::Private,
            'title' => '',
            'slug' => '',
        ];
        //$this->form->fill();
        $this->dispatch('open-modal', id: 'edit-note');
    }


    protected function updateNote(): void
    {
        $this->validate();
        $this->editingNote->update($this->form->getState());
        $this->dispatch('close-modal', id: 'edit-note');
        Notification::make()
            ->title('Note updated')
            ->success()
            ->send();
    }

    protected function createNote(): void
    {
        $this->validate();
        Note::create(
            $this->data
            + ['user_id' => auth()->user()->id]
        );
        $this->dispatch('close-modal', id: 'edit-note');
        Notification::make()
            ->title('New note created')
            ->success()
            ->send();
    }

    public function save(): void
    {
        if ($this->editingNote) {
            $this->updateNote();
            $this->editingNote = null;
            return;
        }

        $this->createNote();
    }

    public function cancel(): void
    {
        $message = match ($this->editingNote) {
            null => 'Creating note cancelled',
            default => 'Editing note cancelled',
        };
        $this->editingNote = null;
        $this->dispatch('close-modal', id: 'edit-note');
        Notification::make()
            ->title($message)
            ->info()
            ->send();

    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->extraAttributes(['class'=>'fi-width-5xl'])
            ->schema([
                Select::make('visibility')
                ->options(NoteVisibility::class)
                ->default('private')
                ->required(),
            TextInput::make('title')
                ->required()
                ->minLength(3)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
            TextInput::make('slug')
                ->required()
                ->unique(Note::class, 'slug'),
            RichEditor::make('body')
                ->json()
                ->fileAttachmentsVisibility('private')
                ->columnSpanFull()
                ->customBlocks([
                    CodeBlock::class,
                ])
                //->activePanel('customBlocks')
                ->toolbarButtons([
                    ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript'],
                    ['clearFormatting'],
                    ['details'],
                    ['h1', 'h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                    ['blockquote', 'bulletList', 'orderedList', 'horizontalRule'],
                    ['link'],
                    ['table', 'attachFiles', 'mergeTags', 'customBlocks'], // The `customBlocks` and `mergeTags` tools are also added here if those features are used.
                    ['undo', 'redo'],
                ])
        ])
            ->statePath('data'); // <-- all values stored in $this->data
    }

    /* END */



    public function notes()
    {
        return $this->queryNodeList();
        /*return Note::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(5);*/
    }


    //TODO: make this dinamic based on search
    public static function getNavigationLabel(): string
    {
        return __('Main');
    }

    public function getHeader(): ?View
    {
        return view('filament.user.pages.front-page-header');
    }

    //TODO: make this dinamic based on search
    public function getTitle(): string|Htmlable
    {
        return 'Scriptum';
    }

    /*public function getHeading(): string
    {
        return __('Welcome to the world of quick notes...');
    }*/

    /*public function getSubheading(): ?string
    {
        return __('Custom Page Subheading');
    }*/


    protected function queryNodeList()
    {
        $builder = Note::frontPage(auth()->user());

        //$string = '"' . $this->search . '"';
        $string = $this->search;
        //$string = preg_replace('/[^A-Za-z0-9 \-\*\"]/', '', $string);

        if ('' !== $string) {
            //dump($string);
            //$string = '"co"* -"whe"*';
            //$string = '"co"*';
            //dump($string);
            $builder->search($string, $this->partial);
        } else {
            $builder->orderBy('created_at', 'desc');
        }
        return $builder->paginate(10);
    }

/*
    public function render(): View
    {
        return view('filament.user.pages.front-page')
            ->with('notes', $this->queryNodeList())
        ;
    }*/

}

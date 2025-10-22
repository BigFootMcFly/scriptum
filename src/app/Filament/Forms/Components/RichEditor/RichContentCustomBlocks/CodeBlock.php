<?php

namespace App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks;

use Filament\Actions\Action;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Tempest\Highlight\Highlighter;

class CodeBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'code';
    }

    public static function getLabel(): string
    {
        return 'Code block';
    }

    public static function configureEditorAction(Action $action): Action
    {
        // NOTE: this is for the hack to set default value (see below)
        $code_editor_default_language = 'php';

        return $action
            ->modalWidth('5xl')
            ->schema([
                TextInput::make('title'),
                // TODO: get the names from the Language enum
                Select::make('language')
                    ->options([
                        'bash' => 'Bash',
                        'css' => 'Css',
                        'html' => 'Html',
                        'javascript' => 'JavaScript',
                        'json' => 'JSON',
                        'php' => 'Php',
                    ])
                    ->label(function ($component, $state, Set $set) use ($code_editor_default_language) {
                        if ($state === null) {
                            // NOTE: in "customBlock" operation default() does not run, this is a hack to set the default value
                            $set($component, $code_editor_default_language);
                        }
                    })
                    ->default($code_editor_default_language) // @see note above
                    ->required()
                    ->reactive(),
                Toggle::make('collapsed')
                    ->label('The code block is collapsed by default'),
                CodeEditor::make('code')
                    ->reactive()
                    ->language(function (Get $get) {
                        return Language::tryFrom($get('language'));
                    }),
            ]);
    }

    public static function highlightCode(array $config): string
    {
        $highlighter = new Highlighter()->withGutter(startAt: 1);
        $result = $highlighter->parse($config['code'], $config['language']);

        return $result;

    }

    public static function toPreviewHtml(array $config): string
    {
        return view('filament.forms.components.rich-editor.rich-content-custom-blocks.code.preview', $config)
            ->with('highlightedCode', self::highlightCode($config))
            ->render();
    }

    public static function toHtml(array $config, array $data): string
    {
        return view('filament.forms.components.rich-editor.rich-content-custom-blocks.code.index', $config)
            ->with('highlightedCode', self::highlightCode($config))
            ->render();
    }
}

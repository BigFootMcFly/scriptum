# DO NOT USE PREFETCHING!!!! It does not work well with the Viewing Mode Button... :(
    `->spa(hasPrefetching: false)`

# SPA does not work well with the Add New Note Button, do not use it for now...
```
    //NOTE: does not work, still got errors:
        Uncaught (in promise) Component not found: nU0zJagSrTqWRRFpWCYv
        Uncaught Component not found: nU0zJagSrTqWRRFpWCYv
    ->spa()
    ->spaUrlExceptions([
        url('/user/notes*'),
    ])
```

# PHPStan above level 8 is not practical

To implement higher than level 8, is impractical, it would need very excessive code to be written.

Ex.:
```php
CodeEditor::make('code')
    ->reactive()
    ->language(fn (Get $get) => Language::tryFrom($get('language'))),
```
Error:
```
    Parameter #1 $value of static method Filament\Forms\Components\CodeEditor\Enums\Language::tryFrom() expects int|string, mixed given.
    🪪  argument.type
```
Basically `$get` (which is part of livewire) returns `mixed`, that cannot easily be converted to string:

```php
CodeEditor::make('code')
    ->reactive()
    ->language(fn (Get $get) => Language::tryFrom((string)$get('language'))),
```
Error:
```
    Cannot cast mixed to string.
    🪪  cast.string
```

This would need some custom code to ensure that the data is not null.
For string, this is doable, but for array, it would be a nightmare, becouse both the function parameter and the return value would need to by exactly type declared, like:
- array\<string\>
- array\<int, string\>
- array\<string, string\>
- ...

It would need lots of functions, only for this, which is not feasible imho...

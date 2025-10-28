<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap',
        __DIR__.'/config',
        __DIR__.'/public',
        __DIR__.'/resources',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withSkip([
        __DIR__.'/bootstrap/cache/*',
        __DIR__.'/vendor/*',
        __DIR__.'/bootstrap/cache/*',
        __DIR__.'/storage/framework/*',
        __DIR__.'/public/*',
        __DIR__.'/node_modules/*',
        __DIR__.'/resources/js/*',
        __DIR__.'/resources/css/*',
        __DIR__.'/app/Providers/TelescopeServiceProvider.php',
        SimplifyIfReturnBoolRector::class => [
            // NOTE: keep policies more verbose for easier readability
            __DIR__.'/app/Policies/NotePolicy.php',
            __DIR__.'/app/Policies/UserPolicy.php',
        ],
        RemoveAlwaysTrueIfConditionRector::class => [
            __DIR__.'/app/Traits/AssureNotNull.php',
        ],
    ])
    ->withPhpSets()
    ->withPreparedSets(typeDeclarations: true)
    ->withPreparedSets(deadCode: true)
    ->withPreparedSets(codeQuality: true);

<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

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
        __DIR__ . '/bootstrap/cache/*',
        __DIR__ . '/vendor/*',
        __DIR__ . '/bootstrap/cache/*',
        __DIR__ . '/storage/framework/*',
        __DIR__ . '/public/*',
        __DIR__ . '/node_modules/*',
        __DIR__ . '/resources/js/*',
        __DIR__ . '/resources/css/*',
        __DIR__ . '/app/Providers/TelescopeServiceProvider.php'
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets()
    ->withTypeCoverageLevel(20) //max: 58, if met, replace with: ->withPreparedSets(typeDeclarations: true)"
    //->withDeadCodeLevel(0) // max: 54, if met, replace with: ->withPreparedSets(deadCode: true)
    ->withPreparedSets(deadCode: true)
    ->withCodeQualityLevel(0); // max 77, if met, replace with: ->withPreparedSets(codeQuality: true)

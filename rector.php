<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

$paths = [
    __DIR__ . '/app',
    __DIR__ . '/tests',
    __DIR__ . '/routes',
    __DIR__ . '/database/migrations',
];

return RectorConfig::configure()
    ->withPaths($paths)
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        earlyReturn: true,
    );

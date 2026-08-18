<?php

declare(strict_types=1);

/**
 * Rector configuration for automated code refactoring.
 *
 * @author splaa <splaa@users.noreply.github.com>
 */

use Rector\Config\RectorConfig;

$paths = [
    __DIR__ . '/app',
    __DIR__ . '/tests',
    __DIR__ . '/routes',
    __DIR__ . '/database/migrations',
];

return RectorConfig::configure()
    ->withPaths($paths)
    ->withPhpSets(php85: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        earlyReturn: true,
    );

<?php

declare(strict_types=1);


/**
 * Rector configuration for automated code refactoring.
 *
 * @category Configuration
 * @author splaa <splaa@github.com>
 * @license  https://opensource.org/licenses/MIT MIT
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

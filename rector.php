<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([__DIR__.'/src']);
    $rectorConfig->phpVersion(PhpVersion::PHP_81);
    $rectorConfig->importNames();

    // Generic rule
    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::PHP_82,
        SetList::PSR_4,
        SetList::TYPE_DECLARATION,
        SetList::EARLY_RETURN,
    ]);

    // Symfony
    if (is_file(__DIR__.'/var/cache/dev/App_KernelDevDebugContainer.xml')) {
        $rectorConfig->symfonyContainerXml(__DIR__.'/var/cache/dev/App_KernelDevDebugContainer.xml');
    } elseif (is_file(__DIR__.'/var/cache/test/App_KernelTestDebugContainer.xml')) {
        $rectorConfig->symfonyContainerXml(__DIR__.'/var/cache/test/App_KernelTestDebugContainer.xml');
    }
    $rectorConfig->sets([
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        SymfonySetList::SYMFONY_62,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);
};

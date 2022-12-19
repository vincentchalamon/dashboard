<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->notPath('rector.php')
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'global_namespace_import' => [
            'import_classes' => true,
        ],
    ])
    ->setFinder($finder)
;

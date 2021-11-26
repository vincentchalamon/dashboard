<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->notPath('rector.php')
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;

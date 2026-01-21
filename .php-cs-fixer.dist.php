<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('tests/Application/var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;

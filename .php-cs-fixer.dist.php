<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true, // should be last of rules set to avoid override its rules
        // here we override some rules
        'yoda_style' => false,
        'declare_strict_types' => true,
    ])
    ->setFinder($finder)
;

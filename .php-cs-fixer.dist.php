<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

$config = new PhpCsFixer\Config();
return $config->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'declare_strict_types' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'visibility_required' => [
            'elements' => ['const', 'method', 'property'],
        ],
        'single_quote' => true,
        'blank_line_after_opening_tag' => true,
    ])
    ->setFinder($finder);

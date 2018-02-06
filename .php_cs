<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP71Migration' => true,
        '@PHP71Migration:risky' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_annotation_without_dot' => false,
        'php_unit_strict' => true,
        'no_useless_else' => true,
        'no_short_echo_tag' => true,
        'no_null_property_initialization' => true,
        'no_php4_constructor' => true,
        'no_unreachable_default_argument_value' => true,
        'combine_consecutive_issets' => true,
        'strict_comparison' => true,
        'declare_strict_types' => true,
        'dir_constant' => true,
        'array_syntax' => ['syntax' => 'short'],
        'strict_param' => true,
        'phpdoc_align' => false,
        'concat_space' => false,
        'phpdoc_summary' => false,
        'linebreak_after_opening_tag' => true,
        'phpdoc_separation' => false,
        'self_accessor' => false,
        'yoda_style' => false,
        'increment_style' => ['style' => 'post'],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;

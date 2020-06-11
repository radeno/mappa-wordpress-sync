<?php

require_once __DIR__.'/PrettierPHPFixer.php';

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('wp-admin')
    ->exclude('wp-includes')
    ->exclude('wp-admin.nosync')
    ->exclude('wp-includes.nosync')
    ->exclude('node_modules')
    ->exclude('wp-content')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->registerCustomFixers([
        (new PrettierPHPFixer()),
    ])
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'align_single_space_minimal'],
        'Prettier/php' => true,
    ])
    ->setFinder($finder)
;

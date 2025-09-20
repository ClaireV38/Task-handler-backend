<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/app')
    ->in(__DIR__ . '/database')
    ->in(__DIR__ . '/routes')
    ->in(__DIR__ . '/tests');

return (new Config())    // ✅ nouvelle syntaxe
->setRules([
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
])
    ->setFinder($finder);


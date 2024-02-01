<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder)
;

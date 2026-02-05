<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->exclude('tests' . DIRECTORY_SEPARATOR . 'data')
    ->exclude('tests' . DIRECTORY_SEPARATOR . 'external')
    ->exclude('vendor')
    ->in(__DIR__ . DIRECTORY_SEPARATOR . 'src')
    ->in(__DIR__ . DIRECTORY_SEPARATOR . 'tests');

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => false,
        'concat_space' => false,
        'phpdoc_order' => true,
        'cast_spaces' => true,
        'declare_strict_types' => false,
        'yoda_style' => [
            'equal' => true,
            'identical' => true,
            'less_and_greater' => true,
        ],
    ])
    ->setFinder($finder);

return $config;

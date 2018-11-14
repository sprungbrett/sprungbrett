<?php

$header = <<<'EOF'
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'ordered_imports' => true,
        'concat_space' => ['spacing' => 'one'],
        'array_syntax' => ['syntax' => 'short'],
        'php_unit_construct' => true,
        'phpdoc_align' => false,
        'class_definition' => [
            'multiLineExtendsEachSingleLine' => true,
        ],
        'declare_strict_types' => true,
        'header_comment' => ['header' => $header],
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->exclude('cache')
            ->in(__DIR__)
    );

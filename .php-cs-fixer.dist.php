<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->append([
        __FILE__,
        'swift.php',
        'Resource/iban_registry_202009r88.php',
        'Resource/iban_registry_202205r92.php',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'php_unit_method_casing' => false,
        'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
    ])
    ->setFinder($finder)
;

<?php

/*
 * This file is part of the iban-validation library.
 *
 * (c) Jan Schädlich <mail@janschaedlich.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This script reads the data from the iban_registry.txt provided by SWIFT and converts it into a yaml structure.
 * An up to date iban registry file can be found here: https://www.swift.com/taxonomy/term/3876
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 */

$usage = "php swift.php iban_registry.txt > Swift/iban_registry.yaml";

if (2 !== $argc) {
    echo 'Please provide path to iban_registry file provided by SWIFT!' . PHP_EOL;
    echo 'Use: ' . $usage . PHP_EOL;
    exit(1);
}

$filename = __DIR__ . '/' . $argv[1];

if (!file_exists($filename)) {
    echo 'Given iban_registry file does not exist!' . PHP_EOL;
    exit(1);
}


require_once __DIR__ . '/vendor/autoload.php';

use Iban\Validation\Swift\RegexConverter;
use Symfony\Component\Yaml\Yaml;

$lines = file($filename);

$countryCodes = [];
$countryNames = [];
$ibanStructure = [];
$bbanStructure = [];
$ibanLength = [];
$bbanLength = [];
$ibanElectronicFormatExamples = [];
$ibanPrintFormatExamples = [];
$branchIdentifierPosition = [];
$branchIdentifierStructure = [];

foreach ($lines as $lineNumber => $line) {
    if (strpos($line, 'IBAN prefix country code (ISO 3166)') !== false) {
        $countryCodes = preg_split('/\t+/', $line);
    }
    if (strpos($line, 'Name of country') !== false) {
        $countryNames = preg_split('/\t+/', $line);
    }
    if (strpos($line, 'IBAN structure') !== false) {
        $ibanStructure = preg_split('/\t+/', $line);
    }
    if (strpos($line, 'BBAN structure') !== false) {
        $bbanStructure = preg_split('/\t+/', $line);
    }
    if (strpos($line, 'IBAN length') !== false) {
        $ibanLength = preg_split('/\t+/', $line);
    }
    if (strpos($line, 'BBAN length') !== false) {
        $bbanLength = preg_split('/\t+/', $line);
    }
    if (strpos($line, 'IBAN electronic format example') !== false) {
        $ibanElectronicFormatExamples = preg_split('/\t+/', $line);
    }
    if (strpos($line, 'IBAN print format example') !== false) {
        $ibanPrintFormatExamples = preg_split('/\t+/', $line);
    }
    if (strpos($line, 'Branch identifier position within the BBAN') !== false) {
        $branchIdentifierPosition = preg_split('/\t+/', $line);
    }
    if (strpos($line, 'Branch identifier pattern') !== false) {
        $branchIdentifierStructure = preg_split('/\t+/', $line);
    }

}

$regexConverter = new RegexConverter();

$registry = [];
foreach ($countryCodes as $key => $countryCode) {
    if (0 === $key) {
        continue;
    }

    $registry[trim($countryCode)] = [
        'country_name' => trim($countryNames[$key]),
        'iban_structure' => trim($ibanStructure[$key]),
        'bban_structure' => trim($bbanStructure[$key]),
        'iban_regex' => '/^' . $regexConverter->convert(trim($ibanStructure[$key])) . '$/',
        'bban_regex' => '/^' . $regexConverter->convert(trim($bbanStructure[$key])) . '$/',
        'iban_length' => intval(trim($ibanLength[$key])),
        'bban_length' => intval(trim($bbanLength[$key])),
        'iban_electronic_format_example' => trim($ibanElectronicFormatExamples[$key]),
        'iban_print_format_example' => trim($ibanPrintFormatExamples[$key]),
        'branch_identifier_position' => trim($branchIdentifierPosition[$key]),
        'branch_identifier_structure' => trim($branchIdentifierStructure[$key]),
        'branch_identifier_regex' => empty(trim($branchIdentifierStructure[$key])) ? '' : '/^' . $regexConverter->convert(trim($branchIdentifierStructure[$key])) . '$/',
    ];
}

echo(Yaml::dump($registry));

exit(0);

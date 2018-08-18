<?php

/*
 * This file is part of the iban-validation library.
 *
 * (c) Jan Schädlich <mail@janschaedlich.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__ . '/../vendor/autoload.php';

$lines = file(__DIR__ . '/iban_registry-201708r78.txt');

$countryCodes = [];
$countryNames = [];
$ibanStructure = [];
$bbanStructure = [];
$ibanLength = [];
$bbanLength = [];
$ibanElectronicFormatExamples = [];
$ibanPrintFormatExamples = [];

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
}

$regexConverter = new \Iban\Validation\Swift\RegexConverter();

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
    ];
}

echo(\Symfony\Component\Yaml\Yaml::dump($registry));

# iban-validation

[![StandWithUkraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/badges/StandWithUkraine.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)

[![SWUbanner](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)

## A message to Russian 🇷🇺 people

If you currently live in Russia, please read [this message](./ToRussianPeople.md).

## Purpose

A small library for validating International Bankaccount Numbers (IBANs) based on the IBAN Registry provided by SWIFT.
See https://www.swift.com/standards/data-standards/iban for more information.

[![.github/workflows/ci.yaml](https://github.com/jschaedl/iban-validation/actions/workflows/ci.yaml/badge.svg?branch=master)](https://github.com/jschaedl/iban-validation/actions/workflows/ci.yaml)
[![PHP Version](https://img.shields.io/badge/version-PHP%208.0%2B-lightblue.svg)](https://img.shields.io/badge/version-PHP%207.4%2B-lightgrey.svg)
[![Latest Stable Version](https://poser.pugx.org/jschaedl/iban-validation/v/stable)](https://packagist.org/packages/jschaedl/iban-validation) 
[![Latest Unstable Version](https://poser.pugx.org/jschaedl/iban-validation/v/unstable)](https://packagist.org/packages/jschaedl/iban-validation) 
[![Total Downloads](https://poser.pugx.org/jschaedl/iban-validation/downloads)](https://packagist.org/packages/jschaedl/iban-validation) 
[![License](https://poser.pugx.org/jschaedl/iban-validation/license)](https://packagist.org/packages/jschaedl/iban-validation) 


## Development status

This library is ready to use. The Iban validation should be fine, but there is no warranty. **Please use it at your own risk.**

---

## Features

* full country support of IBAN validation based on SWIFT Registry
* customizable violation messages
* simple to use object-oriented api
* high test coverage
* DIC friendly

---

## Installation

To install `jschaedl/iban-validation` via [composer](https://getcomposer.org/) use

```sh
$ composer require jschaedl/iban-validation
```

You can see this library on [Packagist](https://packagist.org/packages/jschaedl/iban-validation).

---

## Iban Validation

```php
<?php

use Iban\Validation\Validator;
use Iban\Validation\Iban;

$iban = new Iban('DE89 3704 0044 0532 0130 00');
$validator = new Validator();

if (!$validator->validate($iban)) {
    foreach ($validator->getViolations() as $violation) {
        echo $violation;
    }
}
```

You can also customize the violation messages by providing them via configuration. Just create a `Validator` passing a config array as constructor argument.

```php
<?php

use Iban\Validation\Validator;

$validator = new Validator([
    'violation.unsupported_country' => 'The requested country is not supported!',
    'violation.invalid_length' => 'The length of the given Iban is not valid!',
    'violation.invalid_format' => 'The format of the given Iban is not valid!',
    'violation.invalid_checksum' => 'The checksum of the given Iban is not valid!',
]);
```

You can pass `true` to the second argument of the `Validator::validate(string|Iban $iban, bool $throw = false)` in order
to retrieve exceptions thrown on validation errors.

```php
$validator = new Validator();

try {
    $validator->validate(new Iban('DE89 3704 0044 0532 0130 00'), throw: true);
} catch (Exception $exception) {
    // ...
}
```

## Iban Information

```php
<?php

use Iban\Validation\Iban;
use Iban\Validation\CountryInfo;

$iban = new Iban('IBAN DE89 3704 0044 0532 0130 00');
$iban->countryCode(); // 'DE'
$iban->checksum(); // '89'
$iban->bban(); // '370400440532013000'
$iban->bbanBankIdentifier(); // '37040044'
$iban->format(Iban::FORMAT_PRINT); // 'DE89 3704 0044 0532 0130 00'
$iban->format(Iban::FORMAT_ELECTRONIC); // 'DE89370400440532013000'
$iban->format(Iban::FORMAT_ANONYMIZED); // 'XXXXXXXXXXXXXXXXXX3000'

$countryInfo = new CountryInfo('DE');
$countryInfo->getCountryName(); // 'Germany'
$countryInfo->getIbanStructureSwift(); // 'DE2!n8!n10!n'
$countryInfo->getBbanStructureSwift(); // '8!n10!n'
$countryInfo->getIbanRegex(); // '/^DE\d{2}\d{8}\d{10}$/'
$countryInfo->getBbanRegex(); // '/^\d{8}\d{10}$/'
$countryInfo->getIbanLength(); // 22
$countryInfo->getBbanLength(); // 18
$countryInfo->getIbanPrintExample(); // 'DE89 3704 0044 0532 0130 00'
$countryInfo->getIbanElectronicExample(); // 'DE89370400440532013000'

```

---
 
## How to contribute

If you want to fix some bugs or want to enhance some functionality, please fork one of the release branches and create your own development branch.
Then fix the bug you found or add your enhancements and make a pull request. Please commit your changes in tiny steps and add a detailed description on every commit.

All pull requests must be accompanied by following coding style and static code analysis rules and passing unit tests.
You can run all checks and tests by executing:

```sh
$ make it
```

---
   
## Author

[Jan Schädlich](https://www.linkedin.com/in/janschaedlich)

[Contributors](https://github.com/jschaedl/iban-validation/graphs/contributors)

## License

MIT License

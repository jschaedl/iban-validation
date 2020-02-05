# iban-validation

A small library for validating International Bankaccount Numbers (IBANs) based on the IBAN Registry provided by SWIFT.
See https://www.swift.com/standards/data-standards/iban for more information.

[![Build Status](https://travis-ci.org/jschaedl/iban-validation.png)](https://travis-ci.org/jschaedl/iban-validation)
![PHP Version](https://img.shields.io/badge/version-PHP%207.1%2B-lightgrey.svg)

[![Total Downloads](https://poser.pugx.org/jschaedl/iban-validation/downloads)](https://packagist.org/packages/jschaedl/iban-validation) 
[![Latest Stable Version](https://poser.pugx.org/jschaedl/iban-validation/v/stable)](https://packagist.org/packages/jschaedl/iban-validation) 
[![Latest Unstable Version](https://poser.pugx.org/jschaedl/iban-validation/v/unstable)](https://packagist.org/packages/jschaedl/iban-validation) 
[![License](https://poser.pugx.org/jschaedl/iban-validation/license)](https://packagist.org/packages/jschaedl/iban-validation) 


## Development status

This library is ready to use. The Iban validation should be fine, but there is no warranty. **Please use it at your own risk.**

---

## Features

* full country support of IBAN validation based on SWIFT Registry
* customizable violation messages
* simple to use object oriented api
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

## Iban Information

```php
<?php

use Iban\Validation\Iban;
use Iban\Validation\CountryInfo;

$iban = new Iban('IBAN DE89 3704 0044 0532 0130 00');
$iban->getCountryCode(); // 'DE'
$iban->getChecksum(); // '89'
$iban->getBban(); // '370400440532013000'
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
If you want to fix some bugs or want to enhance some functionality, please fork the master branch and create your own development branch. 
Then fix the bug you found or add your enhancements and make a pull request. Please commit your changes in tiny steps and add a detailed description on every commit. 

### Unit Testing

All pull requests must be accompanied by passing unit tests. This repository uses phpunit and Composer. 
You must run `composer --dev install` to install this package's dependencies. You can run the tests via:

```sh
$ vendor/bin/phpunit
```

---
   
## Author

[Jan Schädlich](https://www.linkedin.com/in/janschaedlich)

[Contributors](https://github.com/jschaedl/iban-validation/graphs/contributors)

## License

MIT License

---

## Release Status

2020-05-20: [Version 1.5](https://github.com/jschaedl/iban-validation/releases/tag/v1.5) has been released.

* [Bugfix] https://github.com/jschaedl/iban-validation/issues/24: properly fail on non-numeric country codes (@xabbuh)
* [Fix] https://github.com/jschaedl/iban-validation/issues/23: run tests on PHP 7.3 and 7.4 (@xabbuh)

2019-11-25: [Version 1.4](https://github.com/jschaedl/iban-validation/releases/tag/v1.4) has been released.

* added Symfony 5.0 support

2019-04-27: [Version 1.3](https://github.com/jschaedl/iban-validation/releases/tag/v1.3) has been released.

* updated iban registry file
* fixed https://github.com/jschaedl/iban-validation/issues/14: set correct scale for bcmod function

2018-12-14: [Version 1.2](https://github.com/jschaedl/iban-validation/releases/tag/v1.2) has been released.

* updated iban registry file to 201812r80
* changed minimum version for yaml and option-resolver component to ^3.4|^4.1

2018-09-14: [Version 1.1](https://github.com/jschaedl/iban-validation/releases/tag/v1.1) has been released.

* introduced `FORMAT_ANONYMIZED` IBAN format: `$iban->format(Iban::FORMAT_ANONYMIZED)`
* cleanup IBAN creation logic
* cleanup project structure

2018-08-19: [Version 1.0](https://github.com/jschaedl/iban-validation/releases/tag/v1.0) has been released.

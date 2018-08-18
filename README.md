# iban-validation

A small library for validating International Bankaccount Numbers (IBANs) based on the IBAN Registry provided by SWIFT.
See https://www.swift.com/taxonomy/term/3876 for more information.

[![Build Status](https://travis-ci.org/jschaedl/iban-validation.png)](https://travis-ci.org/jschaedl/iban-validation)
![PHP Version](https://img.shields.io/badge/version-PHP%207.1%2B-lightgrey.svg)

[![Total Downloads](https://poser.pugx.org/jschaedl/iban-validation/downloads)](https://packagist.org/packages/jschaedl/iban-validation) 
[![Latest Stable Version](https://poser.pugx.org/jschaedl/iban-validation/v/stable)](https://packagist.org/packages/jschaedl/iban-validation) 
[![Latest Unstable Version](https://poser.pugx.org/jschaedl/iban-validation/v/unstable)](https://packagist.org/packages/jschaedl/iban-validation) 
[![License](https://poser.pugx.org/jschaedl/iban-validation/license)](https://packagist.org/packages/jschaedl/iban-validation) 


## Development status

This library is ready to use. The Iban validation should be fine, but there is no warranty. **Please use it at your own risk.**

---

## Installation

To install jschaedl/iban-validation via composer use

```sh
$ composer require jschaedl/iban-validation
```

## Usage example

```php
<?php

use Iban\Validation\Validator;
use Iban\Validation\Iban;

$iban = new Iban('DE89 3704 0044 0532 0130 00');
$validator = new Validator();

$isValid = $validator->validate($iban);
         
if (!$isValid) {
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
    'violation.invalid_length' => 'The length of the given Iban is too short!',
    'violation.invalid_country_code' => 'The country code of the given Iban is not valid!',
    'violation.invalid_format' => 'The format of the given Iban is not valid!',
    'violation.invalid_checksum' => 'The checksum of the given Iban is not valid!',
]);

```

---
 
## How to contribute
If you want to fix some bugs or want to enhance some functionality, please fork the master branch and create your own development branch. 
Then fix the bug you found or add your enhancements and make a pull request. Please commit your changes in tiny steps and add a detailed description on every commit. 

### Unit Testing

All pull requests must be accompanied by passing unit tests. This repository uses phpunit and Composer. 
You must run `composer --dev install` to install this package's dependencies and `cp phpunit.xml.dist phpunit.xml` 
before the unit tests will run. You can run the test via:

```sh
$ vendor/bin/phpunit -c phpunit.xml Tests/
```

---
   
## Author

[Jan Schädlich](https://github.com/jschaedl)

## License

MIT License

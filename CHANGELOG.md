# CHANGELOG

## [Version 1.8.4](https://github.com/jschaedl/iban-validation/releases/tag/v1.8.4)

Released on December 12th 2022

This is the last bugfix version of major version `1`. Please upgrade to version `2.0.0`.

---

## [Version 1.8.3](https://github.com/jschaedl/iban-validation/releases/tag/v1.8.3)

Released on December 9th 2022

### Fixed

* Fixed https://github.com/jschaedl/iban-validation/issues/77. Thanks to [@jschaedl](https://github.com/jschaedl)!

---

## [Version 1.8.2](https://github.com/jschaedl/iban-validation/releases/tag/v1.8.2)

Released on March 7th 2022

[![StandWithUkraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/badges/StandWithUkraine.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)

[![SWUbanner](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)

---

## [Version 1.8.1](https://github.com/jschaedl/iban-validation/releases/tag/v1.8.1)

Released on December 9th 2021

### Changed

 * Deprecated the usage of class `RegistryLoader`, you should implement the `RegistryLoaderInterface` for custom Loaders. Thanks to [@jschaedl](https://github.com/jschaedl)!

### Fixed

 * Fixed https://github.com/jschaedl/iban-validation/issues/67. Thanks to [@jschaedl](https://github.com/jschaedl)!

---

## [Version 1.8.0](https://github.com/jschaedl/iban-validation/releases/tag/v1.8.0)

Released on December 6th 2021

### Changed

 * Removed symfony/yaml dependency and loaded Swift registry data as PHP array to improve performance. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Added support for symfony/yaml and symfony/option-resolver version `^6`. Thanks to [@Philipp91](https://github.com/Philipp91)!

### Updated

 * Updated iban registry to version 88. Thanks to [@jschaedl](https://github.com/jschaedl)!
    * IBANs for Libya can now be validated.

### Fixed

 * Fixed README.md. Thanks to [@jschaedl](https://github.com/jschaedl)!

---

## [Version 1.7.0](https://github.com/jschaedl/iban-validation/releases/tag/v1.7.0)

Released on December 3rd 2020

### Changed

 * `Registry::isCountryAvailable()` is supposed to be private, you should not rely on it anymore. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * `Iban::getNormalizedIban()` is supposed to be private, use `Iban::format()` instead. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Marked `RegexConverter` as `@internal`, you should not rely on it anymore. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Marked `RegexConverter` as `@final`, you should not extend it anymore. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Marked `Registry` as `@final`, you should not extend it anymore. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Marked `RegistryLoader` as `@final`, you should not extend it anymore. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Marked `CountryInfo` as `@final`, you should not extend it anymore. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Marked `Validator` as `@final`, you should not extend it anymore. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Marked `Iban` as `@final`, you should not extend it anymore. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Changed the PHP version constraint to `>=7.1` to allow PHP 8. Thanks to [@chris-doehring](https://github.com/chris-doehring)!

### Deprecated

 * Deprecated method `Iban::getCountryCode()`, use `Iban::countryCode()` instead. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Deprecated method `Iban::getChecksum()`, use `Iban::checksum()` instead. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Deprecated method `Iban::getBban()`, use `Iban::bban()` instead. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Deprecated method `Iban::getBbanBankIdentifier()`, use `Iban::bbanBankIdentifier()` instead. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Deprecated method `CountryInfo::getBbanBankIdentifierStartPos()`, you should not rely on it anymore. Thanks to [@jschaedl](https://github.com/jschaedl)!
 * Deprecated method `CountryInfo::getBbanBankIdentifierEndPos()`, you should not rely on it anymore. Thanks to [@jschaedl](https://github.com/jschaedl)!

---

## [Version 1.6](https://github.com/jschaedl/iban-validation/releases/tag/v1.6)

Released on May 5th 2020

### Added

 * Added checksum reporting in `InvalidChecksumException`. Thanks to [@tugrul](https://github.com/tugrul)!

### Updated

 * Updated iban registry to version 86. Thanks to [@jschaedl](https://github.com/jschaedl)!

---

## [Version 1.5](https://github.com/jschaedl/iban-validation/releases/tag/v1.5)

Released on February 5th 2020

### Fixed

 * Properly fail on non-numeric country codes. Thanks to [@xabbuh](https://github.com/xabbuh)!

---

## [Version 1.4](https://github.com/jschaedl/iban-validation/releases/tag/v1.4)

Release on November 25th 2019 

### Added

 * Added Symfony 5.0 support. Thanks to [@jschaedl](https://github.com/jschaedl)!

---

## [Version 1.3](https://github.com/jschaedl/iban-validation/releases/tag/v1.3)

Release on April 27th 2019

### Fixed

 * Set correct scale for bcmod function. Thanks to [@CodeDuck42](https://github.com/CodeDuck42)!

### Updated

 * Updated iban registry. Thanks to [@jschaedl](https://github.com/jschaedl)

---

## [Version 1.2](https://github.com/jschaedl/iban-validation/releases/tag/v1.2)

Released on December 14th 2018

### Changed

 * Changed minimum version for yaml and option-resolver component to ^3.4|^4.1. Thanks to [@jschaedl](https://github.com/jschaedl)!

### Updated
 
 * Updated iban registry file to version 80. Thanks to [@jschaedl](https://github.com/jschaedl)!

---

## [Version 1.1](https://github.com/jschaedl/iban-validation/releases/tag/v1.1)

Released on September 14th 2018

### Added

 * Added `FORMAT_ANONYMIZED` IBAN format: `$iban->format(Iban::FORMAT_ANONYMIZED)`. Thanks to [@jschaedl](https://github.com/jschaedl)!

---

## [Version 1.0](https://github.com/jschaedl/iban-validation/releases/tag/v1.0)

Initial release on August 19th 2018

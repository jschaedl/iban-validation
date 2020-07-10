CHANGELOG
=========

2.0.0
-----

 * Removed method `Iban::getCountryCode()`, use `Iban::countryCode()` instead.
 * Removed method `Iban::getChecksum()`, use `Iban::checksum()` instead.
 * Removed method `Iban::getBban()`, use `Iban::bban()` instead.
 * Removed method `Iban::getBbanBankIdentifier()`, use `Iban::bbanBankIdentifier()` instead.
 * Removed method `CountryInfo::getBbanBankIdentifierStartPos()`.
 * Removed method `CountryInfo::getBbanBankIdentifierEndPos()`.
 * Made `RegexConverter` final.
 * Made `Registry` final.
 * Made `RegistryLoader` final.
 * Made `CountryInfo` final.
 * Made `Validator` final.
 * Made `Iban` final.

1.7.0
-----

 * Deprecated method `Iban::getCountryCode()`, use `Iban::countryCode()` instead.
 * Deprecated method `Iban::getChecksum()`, use `Iban::checksum()` instead.
 * Deprecated method `Iban::getBban()`, use `Iban::bban()` instead.
 * Deprecated method `Iban::getBbanBankIdentifier()`, use `Iban::bbanBankIdentifier()` instead.
 * Deprecated method `CountryInfo::getBbanBankIdentifierStartPos()`, you should not rely on it anymore.
 * Deprecated method `CountryInfo::getBbanBankIdentifierEndPos()`, you should not rely on it anymore.
 * `Registry::isCountryAvailable()` is supposed to be private, you should not rely on it anymore.
 * `Iban::getNormalizedIban()` is supposed to be private, use `Iban::format()` instead.
 * Marked `RegexConverter` as `@internal`, you should not rely on it anymore.
 * Marked `RegexConverter` as `@final`, you should not extend it anymore.
 * Marked `Registry` as `@final`, you should not extend it anymore.
 * Marked `RegistryLoader` as `@final`, you should not extend it anymore.
 * Marked `CountryInfo` as `@final`, you should not extend it anymore.
 * Marked `Validator` as `@final`, you should not extend it anymore.
 * Marked `Iban` as `@final`, you should not extend it anymore.
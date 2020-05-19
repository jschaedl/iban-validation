CHANGELOG
=========

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
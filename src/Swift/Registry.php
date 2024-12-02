<?php

/*
 * This file is part of the iban-validation library.
 *
 * (c) Jan Schädlich <mail@janschaedlich.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Iban\Validation\Swift;

use Iban\Validation\Swift\Exception\UnsupportedCountryCodeException;

/**
 * Provides access to the loaded data from the iban_registry text file provided by SWIFT.
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 */
final class Registry
{
    private array $registry;

    public function __construct(?RegistryLoaderInterface $registryLoader = null)
    {
        $this->registry = $registryLoader ? $registryLoader->load() : (new PhpRegistryLoader())->load();
    }

    public function isCountryAvailable(string $countryCode): bool
    {
        return array_key_exists($countryCode, $this->registry);
    }

    /**
     * @throws UnsupportedCountryCodeException
     */
    public function getCountryName(string $countryCode): string
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['country_name']);
    }

    public function getIbanStructure(string $countryCode): string
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['iban_structure']);
    }

    public function getBbanStructure(string $countryCode): string
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['bban_structure']);
    }

    /**
     * @throws UnsupportedCountryCodeException
     */
    public function getIbanRegex(string $countryCode): string
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['iban_regex']);
    }

    /**
     * @throws UnsupportedCountryCodeException
     */
    public function getBbanRegex(string $countryCode): string
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['bban_regex']);
    }

    /**
     * @throws UnsupportedCountryCodeException
     */
    public function getBbanLength(string $countryCode): int
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return intval($this->registry[$countryCode]['bban_length']);
    }

    /**
     * @throws UnsupportedCountryCodeException
     */
    public function getIbanLength(string $countryCode): int
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return intval($this->registry[$countryCode]['iban_length']);
    }

    public function getBbanBankIdentifierStartPos(string $countryCode): int
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        if ('' === $this->registry[$countryCode]['bank_identifier_position']) {
            return 0;
        }

        $positionString = substr($this->registry[$countryCode]['bank_identifier_position'], 0, 3);
        $positionArray = explode('-', $positionString);

        return intval(reset($positionArray)) - 1;
    }

    public function getBbanBankIdentifierEndPos(string $countryCode): int
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        if ('' === $this->registry[$countryCode]['bank_identifier_position']) {
            return intval($this->registry[$countryCode]['iban_length']);
        }

        $positionString = substr($this->registry[$countryCode]['bank_identifier_position'], 0, 3);
        $positionArray = explode('-', $positionString);

        return intval(end($positionArray));
    }

    /**
     * @throws UnsupportedCountryCodeException
     */
    public function getIbanElectronicFormatExample(string $countryCode): string
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['iban_electronic_format_example']);
    }

    /**
     * @throws UnsupportedCountryCodeException
     */
    public function getIbanPrintFormatExample(string $countryCode): string
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['iban_print_format_example']);
    }

    /**
     * @throws UnsupportedCountryCodeException
     */
    private function guardAgainstUnsupportedCountryCode(string $countryCode): void
    {
        if (!$this->isCountryAvailable($countryCode)) {
            throw new UnsupportedCountryCodeException($countryCode);
        }
    }
}

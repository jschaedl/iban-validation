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
 *
 * @final since 1.7
 */
class Registry
{
    /**
     * @var array
     */
    private $registry;

    public function __construct(string $registryFile = null)
    {
            $this->registry = require $registryFile
                ?? dirname(__DIR__, 2) . '/Resource/iban_registry_202009r88.php';
    }

    /**
     * @private since 1.7
     * @param string $countryCode
     * @return bool
     */
    public function isCountryAvailable($countryCode)
    {
        return array_key_exists($countryCode, $this->registry);
    }

    /**
     * @param string $countryCode
     * @return string
     * @throws UnsupportedCountryCodeException
     */
    public function getCountryName($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['country_name']);
    }

    /**
     * @param string $countryCode
     * @return string
     */
    public function getIbanStructure($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['iban_structure']);
    }

    /**
     * @param string $countryCode
     * @return string
     */
    public function getBbanStructure($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['bban_structure']);
    }

    /**
     * @param string $countryCode
     * @return string
     * @throws UnsupportedCountryCodeException
     */
    public function getIbanRegex($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['iban_regex']);
    }

    /**
     * @param string $countryCode
     * @return string
     * @throws UnsupportedCountryCodeException
     */
    public function getBbanRegex($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['bban_regex']);
    }

    /**
     * @param string $countryCode
     * @return int
     * @throws UnsupportedCountryCodeException
     */
    public function getBbanLength($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return intval($this->registry[$countryCode]['bban_length']);
    }

    /**
     * @param string $countryCode
     * @return int
     * @throws UnsupportedCountryCodeException
     */
    public function getIbanLength($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return intval($this->registry[$countryCode]['iban_length']);
    }

    public function getBbanBankIdentifierStartPos($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        if ('' === $this->registry[$countryCode]['bank_identifier_position']) {
            return 0;
        }

        $positionString = substr($this->registry[$countryCode]['bank_identifier_position'], 0, 3);
        $positionArray = explode('-', $positionString);

        return intval(reset($positionArray))-1;
    }

    public function getBbanBankIdentifierEndPos($countryCode)
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
     * @param string $countryCode
     * @return string
     * @throws UnsupportedCountryCodeException
     */
    public function getIbanElectronicFormatExample($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['iban_electronic_format_example']);
    }

    /**
     * @param string $countryCode
     * @return string
     * @throws UnsupportedCountryCodeException
     */
    public function getIbanPrintFormatExample($countryCode)
    {
        $this->guardAgainstUnsupportedCountryCode($countryCode);

        return strval($this->registry[$countryCode]['iban_print_format_example']);
    }

    /**
     * @param string $countryCode
     * @throws UnsupportedCountryCodeException
     */
    private function guardAgainstUnsupportedCountryCode($countryCode)
    {
        if (!$this->isCountryAvailable($countryCode)) {
            throw new UnsupportedCountryCodeException($countryCode);
        }
    }
}

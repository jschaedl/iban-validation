<?php

/*
 * This file is part of the iban-validation library.
 *
 * (c) Jan Schädlich <mail@janschaedlich.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Iban\Validation;

use Iban\Validation\Swift\Exception\UnsupportedCountryCodeException;
use Iban\Validation\Swift\Registry;

/**
 * Represents IBAN country information.
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 */
final class CountryInfo
{
    private string $countryCode;

    private Registry $swiftRegistry;

    public function __construct(string $countryCode, ?Registry $swiftRegistry = null)
    {
        $this->countryCode = $countryCode;
        $this->swiftRegistry = $swiftRegistry ?? new Registry();

        if (!$this->swiftRegistry->isCountryAvailable($this->countryCode)) {
            throw new UnsupportedCountryCodeException($this->countryCode);
        }
    }

    public function getCountryName(): string
    {
        return $this->swiftRegistry->getCountryName($this->countryCode);
    }

    public function getIbanStructureSwift(): string
    {
        return $this->swiftRegistry->getIbanStructure($this->countryCode);
    }

    public function getBbanStructureSwift(): string
    {
        return $this->swiftRegistry->getBbanStructure($this->countryCode);
    }

    public function getIbanRegex(): string
    {
        return $this->swiftRegistry->getIbanRegex($this->countryCode);
    }

    public function getBbanRegex(): string
    {
        return $this->swiftRegistry->getBbanRegex($this->countryCode);
    }

    public function getIbanLength(): int
    {
        return $this->swiftRegistry->getIbanLength($this->countryCode);
    }

    public function getBbanLength(): int
    {
        return $this->swiftRegistry->getBbanLength($this->countryCode);
    }

    public function getIbanElectronicExample(): string
    {
        return $this->swiftRegistry->getIbanElectronicFormatExample($this->countryCode);
    }

    public function getIbanPrintExample(): string
    {
        return $this->swiftRegistry->getIbanPrintFormatExample($this->countryCode);
    }
}

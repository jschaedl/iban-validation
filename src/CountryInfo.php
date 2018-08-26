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
class CountryInfo
{
    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var Registry
     */
    private $swiftRegistry;

    /**
     * @param string $countryCode
     * @param null|Registry $swiftRegistry
     */
    public function __construct($countryCode, $swiftRegistry = null)
    {
        $this->countryCode = $countryCode;
        $this->swiftRegistry = $swiftRegistry;

        if (null === $swiftRegistry) {
            $this->swiftRegistry = new Registry();
        }

        if (!$this->swiftRegistry->isCountryAvailable($this->countryCode)){
            throw new UnsupportedCountryCodeException($this->countryCode);
        }
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->swiftRegistry->getCountryName($this->countryCode);
    }

    public function getIbanStructureSwift()
    {
        return $this->swiftRegistry->getIbanStructure($this->countryCode);
    }

    public function getBbanStructureSwift()
    {
        return $this->swiftRegistry->getBbanStructure($this->countryCode);
    }

    public function getIbanRegex()
    {
        return $this->swiftRegistry->getIbanRegex($this->countryCode);
    }

    public function getBbanRegex()
    {
        return $this->swiftRegistry->getBbanRegex($this->countryCode);
    }

    public function getIbanLength()
    {
        return $this->swiftRegistry->getIbanLength($this->countryCode);
    }

    public function getBbanLength()
    {
        return $this->swiftRegistry->getBbanLength($this->countryCode);
    }

    public function getIbanElectronicExample()
    {
        return $this->swiftRegistry->getIbanElectronicFormatExample($this->countryCode);
    }

    public function getIbanPrintExample()
    {
        return $this->swiftRegistry->getIbanPrintFormatExample($this->countryCode);
    }
}

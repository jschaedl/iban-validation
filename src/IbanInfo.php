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
class IbanInfo
{
    /**
     * @var Iban
     */
    private $iban;

    /**
     * @var Registry
     */
    private $swiftRegistry;

    /**
     * @param Iban $iban
     * @param null|Registry $swiftRegistry
     */
    public function __construct($iban, $swiftRegistry = null)
    {
        $this->iban = $iban;
        $this->swiftRegistry = $swiftRegistry;

        if (null === $swiftRegistry) {
            $this->swiftRegistry = new Registry();
        }

        if (!$this->swiftRegistry->isCountryAvailable($this->iban->getCountryCode())){
            throw new UnsupportedCountryCodeException($this->iban->getCountryCode());
        }
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->swiftRegistry->getCountryName($this->iban->getCountryCode());
    }

    public function getIbanStructureSwift()
    {
        return $this->swiftRegistry->getIbanStructure($this->iban->getCountryCode());
    }

    public function getBbanStructureSwift()
    {
        return $this->swiftRegistry->getBbanStructure($this->iban->getCountryCode());
    }

    public function getIbanRegex()
    {
        return $this->swiftRegistry->getIbanRegex($this->iban->getCountryCode());
    }

    public function getBbanRegex()
    {
        return $this->swiftRegistry->getBbanRegex($this->iban->getCountryCode());
    }

    public function getIbanLength()
    {
        return $this->swiftRegistry->getIbanLength($this->iban->getCountryCode());
    }

    public function getBbanLength()
    {
        return $this->swiftRegistry->getBbanLength($this->iban->getCountryCode());
    }

    public function getIbanElectronicExample()
    {
        return $this->swiftRegistry->getIbanElectronicFormatExample($this->iban->getCountryCode());
    }

    public function getIbanPrintExample()
    {
        return $this->swiftRegistry->getIbanPrintFormatExample($this->iban->getCountryCode());
    }
}

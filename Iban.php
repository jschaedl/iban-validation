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

/**
 * Represents an International Bank Account Number (IBAN).
 *
 * LLCC IIII IIII BBBB BBBB BB
 * L => Locale
 * C => Checksum
 * I => Institute Identification
 * B => Bankaccount Number
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 */
class Iban
{
    const LOCALE_CODE_OFFSET = 0;
    const LOCALE_CODE_LENGTH = 2;

    const CHECKSUM_OFFSET = 2;
    const CHECKSUM_LENGTH = 2;

    const INSTITUTE_IDENTIFICATION_OFFSET = 4;
    const INSTITUTE_IDENTIFICATION_LENGTH = 8;

    const BANKACCOUNT_NUMBER_OFFSET = 12;
    const BANKACCOUNT_NUMBER_LENGTH = 10;

    const IBAN_MIN_LENGTH = 15;
    const ACCOUNT_IDENTIFICATION_OFFSET = 4;
    
    private $iban;

    public function __construct($iban)
    {
        $this->iban = $this->normalize($iban);
    }

    public function __toString()
    {
        return $this->iban;
    }

    public function format()
    {
        return sprintf(
            '%s %s %s %s %s %s',
            $this->getLocaleCode() . $this->getChecksum(),
            substr($this->getInstituteIdentification(), 0, 4),
            substr($this->getInstituteIdentification(), 4, 4),
            substr($this->getBankAccountNumber(), 0, 4),
            substr($this->getBankAccountNumber(), 4, 4),
            substr($this->getBankAccountNumber(), 8, 2)
        );
    }

    public function getLocaleCode()
    {
        return substr($this->iban, Iban::LOCALE_CODE_OFFSET, Iban::LOCALE_CODE_LENGTH);
    }

    public function getChecksum()
    {
        return substr($this->iban, Iban::CHECKSUM_OFFSET, Iban::CHECKSUM_LENGTH);
    }

    public function getAccountIdentification()
    {
        return substr($this->iban, Iban::ACCOUNT_IDENTIFICATION_OFFSET);
    }

    public function getInstituteIdentification()
    {
        return substr($this->iban, Iban::INSTITUTE_IDENTIFICATION_OFFSET, Iban::INSTITUTE_IDENTIFICATION_LENGTH);
    }

    public function getBankAccountNumber()
    {
        return substr($this->iban, Iban::BANKACCOUNT_NUMBER_OFFSET, Iban::BANKACCOUNT_NUMBER_LENGTH);
    }
    
    private function normalize($iban)
    {
        return preg_replace('/\s+/', '', trim($iban));
    }
}

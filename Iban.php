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
    public const IBAN_MIN_LENGTH = 15;

    private const LOCALE_CODE_OFFSET = 0;
    private const LOCALE_CODE_LENGTH = 2;

    private const CHECKSUM_OFFSET = 2;
    private const CHECKSUM_LENGTH = 2;

    private const INSTITUTE_IDENTIFICATION_OFFSET = 4;
    private const INSTITUTE_IDENTIFICATION_LENGTH = 8;

    private const BANKACCOUNT_NUMBER_OFFSET = 12;
    private const BANKACCOUNT_NUMBER_LENGTH = 10;

    private const ACCOUNT_IDENTIFICATION_OFFSET = 4;

    /**
     * @var string
     */
    private $iban;

    /**
     * @param string $iban
     */
    public function __construct($iban)
    {
        $this->iban = $this->normalize($iban);
    }

    public function __toString()
    {
        return $this->iban;
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    public function getLocaleCode()
    {
        return substr($this->iban, self::LOCALE_CODE_OFFSET, self::LOCALE_CODE_LENGTH);
    }

    /**
     * @return string
     */
    public function getChecksum()
    {
        return substr($this->iban, self::CHECKSUM_OFFSET, self::CHECKSUM_LENGTH);
    }

    /**
     * @return string
     */
    public function getAccountIdentification()
    {
        return substr($this->iban, self::ACCOUNT_IDENTIFICATION_OFFSET);
    }

    /**
     * @return string
     */
    public function getInstituteIdentification()
    {
        return substr($this->iban, self::INSTITUTE_IDENTIFICATION_OFFSET, self::INSTITUTE_IDENTIFICATION_LENGTH);
    }

    /**
     * @return string
     */
    public function getBankAccountNumber()
    {
        return substr($this->iban, self::BANKACCOUNT_NUMBER_OFFSET, self::BANKACCOUNT_NUMBER_LENGTH);
    }

    /**
     * @param string $iban
     * @return string
     */
    private function normalize($iban)
    {
        return preg_replace('/\s+/', '', trim($iban));
    }
}

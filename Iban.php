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
 * LLCC BBBB BBBB BBBB BBBB BB
 * L => Locale
 * C => Checksum
 * B => BBAN
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 */
class Iban
{
    public const FORMAT_ELECTRONIC = 'electronic';
    public const FORMAT_PRINT = 'print';

    private const COUNTRY_CODE_OFFSET = 0;
    private const COUNTRY_CODE_LENGTH = 2;
    private const CHECKSUM_OFFSET = 2;
    private const CHECKSUM_LENGTH = 2;
    private const BBAN_OFFSET = 4;

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
     * @param string $type
     * @return string
     */
    public function format($type = self::FORMAT_PRINT)
    {
        switch ($type) {
            case self::FORMAT_ELECTRONIC:
                return $this->iban;
                break;
            case self::FORMAT_PRINT:
                return wordwrap($this->iban, 4, ' ', true);
                break;
        }
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return substr($this->iban, self::COUNTRY_CODE_OFFSET, self::COUNTRY_CODE_LENGTH);
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
    public function getBban()
    {
        return substr($this->iban, self::BBAN_OFFSET);
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

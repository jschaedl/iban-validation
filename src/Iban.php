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
 * @author Jan Schädlich <mail@janschaedlich.de>
 */
class Iban
{
    public const FORMAT_ELECTRONIC = 'electronic';
    public const FORMAT_PRINT = 'print';
    public const FORMAT_ANONYMIZED = 'anonymized';

    private const COUNTRY_CODE_OFFSET = 0;
    private const COUNTRY_CODE_LENGTH = 2;
    private const CHECKSUM_OFFSET = 2;
    private const CHECKSUM_LENGTH = 2;
    private const BBAN_OFFSET = 4;

    /**
     * @var string
     */
    private $iban;

    public function __construct(string $iban)
    {
        $this->iban = $iban;
    }

    public function __toString(): string
    {
        return $this->format();
    }

    public function getNormalizedIban(): string
    {
        $iban = $this->iban;

        $iban = trim(strtoupper($iban));
        $iban = preg_replace('/^I?IBAN/', '', $iban);
        $iban = preg_replace('/[^a-zA-Z0-9]/', '', $iban);

        return preg_replace('/\s+/', '', $iban);
    }

    public function format(string $type = self::FORMAT_PRINT): string
    {
        switch ($type) {
            case self::FORMAT_ELECTRONIC:
                return $this->getNormalizedIban();
            case self::FORMAT_PRINT:
                return wordwrap($this->getNormalizedIban(), 4, ' ', true);
            case self::FORMAT_ANONYMIZED:
                return str_pad(substr($this->getNormalizedIban(), -4), strlen($this->getNormalizedIban()), 'X', STR_PAD_LEFT);
            default:
                return $this->iban;
        }
    }

    public function getCountryCode(): string
    {
        return substr($this->getNormalizedIban(), self::COUNTRY_CODE_OFFSET, self::COUNTRY_CODE_LENGTH);
    }

    public function getChecksum(): string
    {
        return substr($this->getNormalizedIban(), self::CHECKSUM_OFFSET, self::CHECKSUM_LENGTH);
    }

    public function getBban(): string
    {
        return substr($this->getNormalizedIban(), self::BBAN_OFFSET);
    }

    public function getBbanBankIdentifier(): string
    {
        $countryInfo = new CountryInfo($this->getCountryCode());

        return substr(
            $this->getBban(),
            $countryInfo->getBbanBankIdentifierStartPos(),
            $countryInfo->getBbanBankIdentifierEndPos()
        );
    }
}

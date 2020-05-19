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

use Iban\Validation\Swift\Registry;

/**
 * Represents an International Bank Account Number (IBAN).
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 *
 * @final since 1.7
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

    /**
     * @param string $iban
     */
    public function __construct($iban)
    {
        $this->iban = $iban;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format();
    }

    /**
     * @private since 1.7
     * @return string
     */
    public function getNormalizedIban()
    {
        $iban = $this->iban;

        $iban = trim(strtoupper($iban));
        $iban = preg_replace('/^I?IBAN/', '', $iban);
        $iban = preg_replace('/[^a-zA-Z0-9]/', '', $iban);

        return preg_replace('/\s+/', '', $iban);
    }

    /**
     * @param string $type
     * @return string
     */
    public function format($type = self::FORMAT_PRINT)
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

    /**
     * @deprecated since 1.7, use method countryCode() instead.
     * @return string
     */
    public function getCountryCode()
    {
        @trigger_error(sprintf('The "%s" method is deprecated since 1.7, use "%s::countryCode()" instead.', __METHOD__, Iban::class), E_USER_DEPRECATED);

        return substr($this->getNormalizedIban(), self::COUNTRY_CODE_OFFSET, self::COUNTRY_CODE_LENGTH);
    }

    public function countryCode(): string
    {
        return substr($this->getNormalizedIban(), self::COUNTRY_CODE_OFFSET, self::COUNTRY_CODE_LENGTH);
    }

    /**
     * @deprecated since 1.7, use method checksum() instead.
     * @return string
     */
    public function getChecksum()
    {
        @trigger_error(sprintf('The "%s" method is deprecated since 1.7, use "%s::checksum()" instead.', __METHOD__, Iban::class), E_USER_DEPRECATED);

        return substr($this->getNormalizedIban(), self::CHECKSUM_OFFSET, self::CHECKSUM_LENGTH);
    }

    public function checksum(): string
    {
        return substr($this->getNormalizedIban(), self::CHECKSUM_OFFSET, self::CHECKSUM_LENGTH);
    }

    /**
     * @deprecated since 1.7, use method bban() instead.
     * @return string
     */
    public function getBban()
    {
        @trigger_error(sprintf('The "%s" method is deprecated since 1.7, use "%s::bban()" instead.', __METHOD__, Iban::class), E_USER_DEPRECATED);

        return substr($this->getNormalizedIban(), self::BBAN_OFFSET);
    }

    public function bban(): string
    {
        return substr($this->getNormalizedIban(), self::BBAN_OFFSET);
    }

    /**
     * @deprecated since 1.7, use method bbanBankIdentifier() instead.
     * @return string
     */
    public function getBbanBankIdentifier()
    {
        @trigger_error(sprintf('The "%s" method is deprecated since 1.7, use "%s::bbanBankIdentifier()" instead.', __METHOD__, Iban::class), E_USER_DEPRECATED);

        $registry = new Registry();

        return substr(
            $this->bban(),
            $registry->getBbanBankIdentifierStartPos($this->countryCode()),
            $registry->getBbanBankIdentifierEndPos($this->countryCode())
        );
    }

    public function bbanBankIdentifier(): string
    {
        $registry = new Registry();

        return substr(
            $this->bban(),
            $registry->getBbanBankIdentifierStartPos($this->countryCode()),
            $registry->getBbanBankIdentifierEndPos($this->countryCode())
        );
    }
}

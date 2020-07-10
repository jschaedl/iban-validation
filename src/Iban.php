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

use Iban\Validation\Exception\CanNotBeNormalizedException;
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

    public function __construct(string $iban)
    {
        $this->iban = $iban;
    }

    public function __toString(): string
    {
        return $this->format();
    }

    /**
     * @private since 1.7
     */
    public function getNormalizedIban(): string
    {
        $normalizedIban = trim(strtoupper($this->iban));

        if (null === $normalizedIban = preg_replace(['/^I?IBAN/', '/[^a-zA-Z0-9]/', '/\s+/'], '', $normalizedIban)) {
            throw new CanNotBeNormalizedException($this->iban);
        }

        return $normalizedIban;
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

    /**
     * @deprecated since 1.7, use method countryCode() instead.
     */
    public function getCountryCode(): string
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
     */
    public function getChecksum(): string
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
     */
    public function getBban(): string
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
     */
    public function getBbanBankIdentifier(): string
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

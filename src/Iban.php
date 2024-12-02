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
 */
final class Iban
{
    public const FORMAT_ELECTRONIC = 'electronic';
    public const FORMAT_PRINT = 'print';
    public const FORMAT_ANONYMIZED = 'anonymized';

    private const COUNTRY_CODE_OFFSET = 0;
    private const COUNTRY_CODE_LENGTH = 2;
    private const CHECKSUM_OFFSET = 2;
    private const CHECKSUM_LENGTH = 2;
    private const BBAN_OFFSET = 4;

    private string $iban;

    private Registry $swiftRegistry;

    public function __construct(string $iban, ?Registry $swiftRegistry = null)
    {
        $this->iban = $iban;

        $this->swiftRegistry = $swiftRegistry ?? new Registry();
    }

    public function __toString(): string
    {
        return $this->format();
    }

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
        return match ($type) {
            self::FORMAT_ELECTRONIC => $this->getNormalizedIban(),
            self::FORMAT_PRINT => wordwrap($this->getNormalizedIban(), 4, ' ', true),
            self::FORMAT_ANONYMIZED => str_pad(substr($this->getNormalizedIban(), -4), strlen($this->getNormalizedIban()), 'X', STR_PAD_LEFT),
            default => $this->iban,
        };
    }

    public function countryCode(): string
    {
        return substr($this->getNormalizedIban(), self::COUNTRY_CODE_OFFSET, self::COUNTRY_CODE_LENGTH);
    }

    public function checksum(): string
    {
        return substr($this->getNormalizedIban(), self::CHECKSUM_OFFSET, self::CHECKSUM_LENGTH);
    }

    public function bban(): string
    {
        return substr($this->getNormalizedIban(), self::BBAN_OFFSET);
    }

    public function bbanBankIdentifier(): string
    {
        return substr(
            $this->bban(),
            $this->swiftRegistry->getBbanBankIdentifierStartPos($this->countryCode()),
            $this->swiftRegistry->getBbanBankIdentifierEndPos($this->countryCode())
        );
    }
}

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

use Iban\Validation\Exception\InvalidChecksumException;
use Iban\Validation\Exception\InvalidFormatException;
use Iban\Validation\Exception\InvalidLengthException;
use Iban\Validation\Swift\Exception\UnsupportedCountryCodeException;
use Iban\Validation\Swift\Registry;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Validates International Bank Account Numbers (IBANs).
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 */
final class Validator
{
    private Registry $swiftRegistry;

    private array $options;

    private array $violations;

    public function __construct(array $options = [], ?Registry $swiftRegistry = null)
    {
        $this->swiftRegistry = $swiftRegistry ?? new Registry();

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);

        $this->violations = [];
    }

    /**
     * @param bool $throw whether an exception should be thrown on validation
     */
    public function validate(string|Iban $iban, bool $throw = false): bool
    {
        if (!$iban instanceof Iban) {
            $iban = new Iban($iban);
        }
        $this->violations = [];

        try {
            $this->validateCountryCode($iban);
        } catch (UnsupportedCountryCodeException $unsupportedCountryCodeException) {
            $this->violations[] = $this->options['violation.unsupported_country'];

            if ($throw) {
                throw $unsupportedCountryCodeException;
            }

            return false; // return here because with an unsupported country code all other checks make no sense at all
        }

        try {
            $this->validateLength($iban);
        } catch (InvalidLengthException $invalidLengthException) {
            $this->violations[] = $this->options['violation.invalid_length'];

            if ($throw) {
                throw $invalidLengthException;
            }
        }

        try {
            $this->validateFormat($iban);
        } catch (InvalidFormatException $invalidFormatException) {
            $this->violations[] = $this->options['violation.invalid_format'];

            if ($throw) {
                throw $invalidFormatException;
            }
        }

        try {
            $this->validateChecksum($iban);
        } catch (InvalidChecksumException $invalidChecksumException) {
            $this->violations[] = $this->options['violation.invalid_checksum'];

            if ($throw) {
                throw $invalidChecksumException;
            }
        }

        return 0 === count($this->violations);
    }

    public function getViolations(): array
    {
        return $this->violations;
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'violation.unsupported_country' => 'The requested country is not supported!',
            'violation.invalid_length' => 'The length of the given Iban is not valid!',
            'violation.invalid_format' => 'The format of the given Iban is not valid!',
            'violation.invalid_checksum' => 'The checksum of the given Iban is not valid!',
        ]);

        $resolver->setAllowedTypes('violation.unsupported_country', 'string');
        $resolver->setAllowedTypes('violation.invalid_length', 'string');
        $resolver->setAllowedTypes('violation.invalid_format', 'string');
        $resolver->setAllowedTypes('violation.invalid_checksum', 'string');
    }

    /**
     * @throws UnsupportedCountryCodeException
     */
    private function validateCountryCode(Iban $iban): void
    {
        if (!$this->swiftRegistry->isCountryAvailable($iban->countryCode())) {
            throw new UnsupportedCountryCodeException($iban->countryCode());
        }
    }

    /**
     * @throws InvalidLengthException
     */
    private function validateLength(Iban $iban): void
    {
        if (strlen($iban->getNormalizedIban()) !== $this->swiftRegistry->getIbanLength($iban->countryCode())) {
            throw new InvalidLengthException($iban->format());
        }
    }

    /**
     * @throws InvalidFormatException
     */
    private function validateFormat(Iban $iban): void
    {
        if (1 !== preg_match($this->swiftRegistry->getIbanRegex($iban->countryCode()), $iban->getNormalizedIban())) {
            throw new InvalidFormatException($iban->format());
        }
    }

    /**
     * @throws InvalidChecksumException
     */
    private function validateChecksum(Iban $iban): void
    {
        $invertedIban = self::convertToBigInt($iban->bban().$iban->countryCode().$iban->checksum());

        if (!preg_match('/^\d+$/', $iban->checksum())) {
            $validChecksum = 98 - intval(self::bigIntModulo97($invertedIban));
            throw new InvalidChecksumException($iban->format(), (string) $validChecksum);
        }

        if ('1' !== self::bigIntModulo97($invertedIban)) {
            $validChecksum = 98 - intval(self::bigIntModulo97($invertedIban));
            throw new InvalidChecksumException($iban->format(), (string) $validChecksum);
        }
    }

    private static function convertToBigInt(string $string): string
    {
        $chars = str_split($string);
        $bigInt = '';

        foreach ($chars as $char) {
            if (ctype_upper($char)) {
                $bigInt .= (\ord($char) - 55);
                continue;
            }
            $bigInt .= $char;
        }

        return $bigInt;
    }

    private static function bigIntModulo97(string $bigInt): string
    {
        $modulus = '97';

        if (function_exists('bcmod')) {
            return bcmod($bigInt, $modulus, 0);
        }

        $take = 5;
        $mod = '';

        do {
            $a = intval($mod.substr($bigInt, 0, $take));
            $bigInt = substr($bigInt, $take);
            $mod = $a % $modulus;
        } while (strlen($bigInt));

        return (string) $mod;
    }
}

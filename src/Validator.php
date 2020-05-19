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
class Validator
{
    /**
     * @var Registry
     */
    private $swiftRegistry;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var array
     */
    private $violations = [];

    public function __construct(array $options = [], Registry $swiftRegistry = null)
    {
        $this->swiftRegistry = $swiftRegistry ?? new Registry();

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    /**
     * @param string|Iban $iban
     */
    public function validate($iban): bool
    {
        if (!$iban instanceof Iban) {
            $iban = new Iban($iban);
        }

        $this->violations = [];

        try {
            $this->validateCountryCode($iban);
        } catch (UnsupportedCountryCodeException $exception) {
            $this->violations[] = $this->options['violation.unsupported_country'];

            return false; // return here because with an unsupported country code all other checks make no sense at all
        }

        try {
            $this->validateLength($iban);
        } catch (InvalidLengthException $exception) {
            $this->violations[] = $this->options['violation.invalid_length'];
        }

        try {
            $this->validateFormat($iban);
        } catch (InvalidFormatException $exception) {
            $this->violations[] = $this->options['violation.invalid_format'];
        }

        try {
            $this->validateChecksum($iban);
        } catch (InvalidChecksumException $exception) {
            $this->violations[] = $this->options['violation.invalid_checksum'];
        }

        return 0 === count($this->violations);
    }

    public function getViolations(): array
    {
        return $this->violations;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'violation.unsupported_country' => 'The requested country is not supported!',
            'violation.invalid_length' => 'The length of the given Iban is not valid!',
            'violation.invalid_format' => 'The format of the given Iban is not valid!',
            'violation.invalid_checksum' => 'The checksum of the given Iban is not valid!',
        ]);
    }

    /**
     * @throws UnsupportedCountryCodeException
     */
    protected function validateCountryCode(Iban $iban): void
    {
        if (!$this->swiftRegistry->isCountryAvailable($iban->getCountryCode())) {
            throw new UnsupportedCountryCodeException($iban);
        }
    }

    /**
     * @throws InvalidLengthException
     */
    protected function validateLength(Iban $iban): void
    {
        if ((strlen($iban->getNormalizedIban()) !== $this->swiftRegistry->getIbanLength($iban->getCountryCode()))) {
            throw new InvalidLengthException($iban);
        }
    }

    /**
     * @throws InvalidLengthException
     */
    protected function validateFormat(Iban $iban): void
    {
        if ((1 !== preg_match($this->swiftRegistry->getIbanRegex($iban->getCountryCode()), $iban->getNormalizedIban()))) {
            throw new InvalidFormatException($iban);
        }
    }

    /**
     * @throws InvalidChecksumException
     */
    protected function validateChecksum(Iban $iban): void
    {
        $invertedIban = self::convertToBigInt($iban->getBban().$iban->getCountryCode().$iban->getChecksum());

        if (!preg_match('/^\d+$/', $iban->getChecksum())) {
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

    private static function bigIntModulo97(string $bigInt): ?string
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

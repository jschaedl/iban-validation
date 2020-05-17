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
     * @var array
     */
    private $letterMap = [
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
        5 => 'E',
        6 => 'F',
        7 => 'G',
        8 => 'H',
        9 => 'I',
        10 => 'J',
        11 => 'K',
        12 => 'L',
        13 => 'M',
        14 => 'N',
        15 => 'O',
        16 => 'P',
        17 => 'Q',
        18 => 'R',
        19 => 'S',
        20 => 'T',
        21 => 'U',
        22 => 'V',
        23 => 'W',
        24 => 'X',
        25 => 'Y',
        26 => 'Z',
    ];

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
        $numericBban = $this->getNumericRepresentation($iban->getBban());
        $numericCountryCode = $this->getNumericRepresentation($iban->getCountryCode());
        $checksum = $iban->getChecksum();

        if (!preg_match('/^\d+$/', $checksum)) {
            $validChecksumIban = $numericBban.$numericCountryCode.'00';
            $validChecksum = 98 - intval($this->local_bcmod($validChecksumIban, '97'));
            throw new InvalidChecksumException($iban, (string) $validChecksum);
        }

        $invertedIban = $numericBban.$numericCountryCode.$checksum;

        if ('1' !== $this->local_bcmod($invertedIban, '97')) {
            $validChecksumIban = $numericBban.$numericCountryCode.'00';
            $validChecksum = 98 - intval($this->local_bcmod($validChecksumIban, '97'));
            throw new InvalidChecksumException($iban, (string) $validChecksum);
        }
    }

    private function getNumericRepresentation(string $letterRepresentation): string
    {
        $numericRepresentation = '';
        foreach (str_split($letterRepresentation) as $char) {
            if (array_search($char, $this->letterMap)) {
                $numericRepresentation .= (int) array_search($char, $this->letterMap) + 9;
            } else {
                $numericRepresentation .= $char;
            }
        }

        return $numericRepresentation;
    }

    private function local_bcmod(string $operand, string $modulus): ?string
    {
        if (function_exists('bcmod')) {
            return PHP_VERSION_ID >= 70200
                ? bcmod($operand, $modulus, 0)
                : bcmod($operand, $modulus);
        }

        $take = 5;
        $mod = '';

        do {
            $a = intval($mod.substr($operand, 0, $take));
            $operand = substr($operand, $take);
            $mod = $a % $modulus;
        } while (strlen($operand));

        return (string) $mod;
    }
}

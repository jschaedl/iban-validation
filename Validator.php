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

use Iban\Validation\Exception\UnexpectedTypeException;
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
        26 => 'Z'
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

    /**
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->swiftRegistry = new Registry();

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    /**
     * @param Iban $iban
     * @return bool
     */
    public function validate($iban)
    {
        if (!$iban instanceof Iban) {
            throw new UnexpectedTypeException($iban, Iban::class);
        }

        $this->violations = [];

        $isValid = false;

        try {
            $isValid = $this->isLengthValid($iban)
                && $this->isCountryCodeValid($iban)
                && $this->isFormatValid($iban)
                && $this->isChecksumValid($iban);
        } catch (UnsupportedCountryCodeException $exception) {
            $this->violations[] = 'violation.unsupported_country';
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function getViolations()
    {
        return $this->violations;
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'violation.unsupported_country' => 'The requested country is not supported!',
            'violation.invalid_length' => 'The length of the given Iban is too short!',
            'violation.invalid_country_code' => 'The country code of the given Iban is not valid!',
            'violation.invalid_format' => 'The format of the given Iban is not valid!',
            'violation.invalid_checksum' => 'The checksum of the given Iban is not valid!',
        ]);
    }

    /**
     * @param Iban $iban
     * @return bool
     */
    private function isLengthValid($iban)
    {
        $isValid = !(strlen($iban) < $this->swiftRegistry->getIbanLength($iban->getCountryCode()));

        if (!$isValid) {
            $this->violations[] = $this->options['violation.invalid_length'];
        }

        return $isValid;
    }

    /**
     * @param Iban $iban
     * @return bool
     */
    private function isCountryCodeValid($iban)
    {
        $isValid = $this->swiftRegistry->isCountryAvailable($iban->getCountryCode());

        if (!$isValid) {
            $this->violations[] = $this->options['violation.invalid_country_code'];
        }

        return $isValid;
    }

    /**
     * @param Iban $iban
     * @return bool
     */
    private function isFormatValid($iban)
    {
        $isValid = !(1 !== preg_match($this->swiftRegistry->getIbanRegex($iban->getCountryCode()), $iban));

        if (!$isValid) {
            $this->violations[] = $this->options['violation.invalid_format'];
        }

        return $isValid;
    }

    /**
     * @param Iban $iban
     * @return bool
     */
    private function isChecksumValid($iban)
    {
        $numericBban = $this->getNumericRepresentation($iban->getBban());
        $numericCountryCode = $this->getNumericRepresentation($iban->getCountryCode());
        $checksum = $iban->getChecksum();

        $invertedIban = $numericBban . $numericCountryCode . $checksum;

        $isValid = '1' === $this->local_bcmod($invertedIban, '97');

        if (!$isValid) {
            $this->violations[] = $this->options['violation.invalid_checksum'];
        }

        return $isValid;
    }

    /**
     * @param string $letterRepresentation
     * @return string
     */
    private function getNumericRepresentation($letterRepresentation)
    {
        $numericRepresentation = '';
        foreach (str_split($letterRepresentation) as $char) {
            if (array_search($char, $this->letterMap)) {
                $numericRepresentation .= array_search($char, $this->letterMap) + 9;
            } else {
                $numericRepresentation .= $char;
            }
        }

        return $numericRepresentation;
    }

    /**
     * @param string $operand
     * @param string $modulus
     * @return string
     */
    private function local_bcmod($operand, $modulus)
    {
        if (!function_exists('bcmod')) {
            $take = 5;
            $mod = '';

            do {
                $a = (int)$mod . substr($operand, 0, $take);
                $operand = substr($operand, $take);
                $mod = $a % $modulus;
            } while (strlen($operand));

            return (string)$mod;
        } else {
            return bcmod($operand, $modulus);
        }
    }
}

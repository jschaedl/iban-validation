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

/**
 * Validates International Bank Account Numbers (IBANs).
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 */
class Validator
{
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

    private $formatMap = [
        'AL' => '[0-9]{8}[0-9A-Z]{16}',
        'AD' => '[0-9]{8}[0-9A-Z]{12}',
        'AT' => '[0-9]{16}',
        'BE' => '[0-9]{12}',
        'BA' => '[0-9]{16}',
        'BG' => '[A-Z]{4}[0-9]{6}[0-9A-Z]{8}',
        'HR' => '[0-9]{17}',
        'CY' => '[0-9]{8}[0-9A-Z]{16}',
        'CZ' => '[0-9]{20}',
        'DK' => '[0-9]{14}',
        'EE' => '[0-9]{16}',
        'FO' => '[0-9]{14}',
        'FI' => '[0-9]{14}',
        'FR' => '[0-9]{10}[0-9A-Z]{11}[0-9]{2}',
        'GE' => '[0-9A-Z]{2}[0-9]{16}',
        'DE' => '[0-9]{18}',
        'GI' => '[A-Z]{4}[0-9A-Z]{15}',
        'GR' => '[0-9]{7}[0-9A-Z]{16}',
        'GL' => '[0-9]{14}',
        'HU' => '[0-9]{24}',
        'IS' => '[0-9]{22}',
        'IE' => '[0-9A-Z]{4}[0-9]{14}',
        'IL' => '[0-9]{19}',
        'IT' => '[A-Z][0-9]{10}[0-9A-Z]{12}',
        'KZ' => '[0-9]{3}[0-9A-Z]{13}',
        'KW' => '[A-Z]{4}[0-9]{22}',
        'LV' => '[A-Z]{4}[0-9A-Z]{13}',
        'LB' => '[0-9]{4}[0-9A-Z]{20}',
        'LI' => '[0-9]{5}[0-9A-Z]{12}',
        'LT' => '[0-9]{16}',
        'LU' => '[0-9]{3}[0-9A-Z]{13}',
        'MK' => '[0-9]{3}[0-9A-Z]{10}[0-9]{2}',
        'MT' => '[A-Z]{4}[0-9]{5}[0-9A-Z]{18}',
        'MR' => '[0-9]{23}',
        'MU' => '[A-Z]{4}[0-9]{19}[A-Z]{3}',
        'MC' => '[0-9]{10}[0-9A-Z]{11}[0-9]{2}',
        'ME' => '[0-9]{18}',
        'NL' => '[A-Z]{4}[0-9]{10}',
        'NO' => '[0-9]{11}',
        'PL' => '[0-9]{24}',
        'PT' => '[0-9]{21}',
        'RO' => '[A-Z]{4}[0-9A-Z]{16}',
        'SM' => '[A-Z][0-9]{10}[0-9A-Z]{12}',
        'SA' => '[0-9]{2}[0-9A-Z]{18}',
        'RS' => '[0-9]{18}',
        'SK' => '[0-9]{20}',
        'SI' => '[0-9]{15}',
        'ES' => '[0-9]{20}',
        'SE' => '[0-9]{20}',
        'CH' => '[0-9]{5}[0-9A-Z]{12}',
        'TN' => '[0-9]{20}',
        'TR' => '[0-9]{5}[0-9A-Z]{17}',
        'AE' => '[0-9]{19}',
        'GB' => '[A-Z]{4}[0-9]{14}'
    ];

    private $violations = [];

    /**
     * @param Iban $iban
     * @return bool
     */
    public function validate($iban)
    {
        if (!$iban instanceof Iban) {
            throw new UnexpectedTypeException($iban, Iban::class);
        }

        return $this->isLengthValid($iban)
            && $this->isLocalCodeValid($iban)
            && $this->isFormatValid($iban)
            && $this->isChecksumValid($iban);
    }

    public function getViolations()
    {
        return $this->violations;
    }

    /**
     * @param Iban $iban
     * @return bool
     */
    private function isLengthValid($iban)
    {
        $isValid = !(strlen($iban) < Iban::IBAN_MIN_LENGTH);

        if (!$isValid) {
            $this->violations[] = 'The lenght of the given Iban is too short!';
        }

        return $isValid;
    }

    /**
     * @param Iban $iban
     * @return bool
     */
    private function isLocalCodeValid($iban)
    {
        $localeCode = $iban->getLocaleCode();

        $isValid = array_key_exists($localeCode, $this->formatMap);

        if (!$isValid) {
            $this->violations[] = 'The locale code of the given Iban is not valid!';
        }

        return $isValid;
    }

    /**
     * @param Iban $iban
     * @return bool
     */
    private function isFormatValid($iban)
    {
        $localeCode = $iban->getLocaleCode();
        $accountIdentification = $iban->getAccountIdentification();

        $isValid = !(1 !== preg_match('/' . $this->formatMap[$localeCode] . '/', $accountIdentification));

        if (!$isValid) {
            $this->violations[] = 'The format of the given Iban is not valid!';
        }

        return $isValid;
    }

    /**
     * @param Iban $iban
     * @return bool
     */
    private function isChecksumValid($iban)
    {
        $localeCode = $iban->getLocaleCode();
        $checksum = $iban->getChecksum();
        $accountIdentification = $iban->getAccountIdentification();
        $numericLocalCode = $this->getNumericLocaleCode($localeCode);
        $numericAccountIdentification = $this->getNumericAccountIdentification($accountIdentification);
        $invertedIban = $numericAccountIdentification . $numericLocalCode . $checksum;

        $isValid = '1' === $this->local_bcmod($invertedIban, '97');

        if (!$isValid) {
            $this->violations[] = 'The checksum of the given Iban is not valid!';
        }

        return $isValid;
    }

    /**
     * @param string $localeCode
     * @return string
     */
    private function getNumericLocaleCode($localeCode)
    {
        return $this->getNumericRepresentation($localeCode);
    }

    /**
     * @param string $accountIdentification
     * @return string
     */
    private function getNumericAccountIdentification($accountIdentification)
    {
        return $this->getNumericRepresentation($accountIdentification);
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

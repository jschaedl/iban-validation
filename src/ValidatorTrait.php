<?php

namespace Iban\Validation;

use Iban\Validation\Exception\InvalidChecksumException;
use Iban\Validation\Exception\InvalidFormatException;
use Iban\Validation\Exception\InvalidLengthException;
use Iban\Validation\Swift\Exception\UnsupportedCountryCodeException;
use Iban\Validation\Swift\Registry;

trait ValidatorTrait
{
    /**
     * @param string|Iban $iban
     */
    public function validate($iban): bool
    {
        if (!$iban instanceof Iban) {
            $iban = new Iban($iban);
        }

        $this->validateCountryCode($iban);

        $this->validateLength($iban);

        $this->validateFormat($iban);

        $this->validateChecksum($iban);

        return true;
    }

    /**
     * @throws UnsupportedCountryCodeException
     */
    private function validateCountryCode(Iban $iban): void
    {
        if (!$this->getSwiftRegistry()->isCountryAvailable($iban->countryCode())) {
            throw new UnsupportedCountryCodeException($iban);
        }
    }

    /**
     * @throws InvalidLengthException
     */
    private function validateLength(Iban $iban): void
    {
        if ((strlen($iban->getNormalizedIban()) !== $this->getSwiftRegistry()->getIbanLength($iban->countryCode()))) {
            throw new InvalidLengthException($iban);
        }
    }

    /**
     * @throws InvalidFormatException
     */
    private function validateFormat(Iban $iban): void
    {
        if ((1 !== preg_match($this->getSwiftRegistry()->getIbanRegex($iban->countryCode()), $iban->getNormalizedIban()))) {
            throw new InvalidFormatException($iban);
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

    /**
     * @return Registry
     */
    protected function getSwiftRegistry()
    {
        return new Registry();
    }
}

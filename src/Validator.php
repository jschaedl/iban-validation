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
final class Validator extends BareValidator
{
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
        parent::__construct($swiftRegistry);

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

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'violation.unsupported_country' => 'The requested country is not supported!',
            'violation.invalid_length' => 'The length of the given Iban is not valid!',
            'violation.invalid_format' => 'The format of the given Iban is not valid!',
            'violation.invalid_checksum' => 'The checksum of the given Iban is not valid!',
        ]);
    }
}

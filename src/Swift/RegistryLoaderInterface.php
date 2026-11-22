<?php

/*
 * This file is part of the iban-validation library.
 *
 * (c) Jan Schädlich <mail@janschaedlich.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Iban\Validation\Swift;

/**
 * Loads the iban_registry data.
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 *
 * @phpstan-type RegistryEntry array{
 *     country_name: string,
 *     iban_structure: string,
 *     bban_structure: string,
 *     iban_regex: string,
 *     bban_regex: string,
 *     iban_length: int,
 *     bban_length: int,
 *     iban_electronic_format_example: string,
 *     iban_print_format_example: string,
 *     bank_identifier_position: string,
 *     bank_identifier_structure: string,
 *     bank_identifier_regex: string,
 *     branch_identifier_position: string,
 *     branch_identifier_structure: string,
 *     branch_identifier_regex: string,
 * }
 * @phpstan-type RegistryData array<string, RegistryEntry>
 */
interface RegistryLoaderInterface
{
    /**
     * @return RegistryData
     */
    public function load(): array;
}

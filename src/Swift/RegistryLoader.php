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

use Iban\Validation\Swift\Exception\RegistryLoaderException;
use Symfony\Component\Yaml\Yaml;

/**
 * Loads the iban_registry text file provided by SWIFT and parses it to yaml.
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 *
 * @final since 1.7
 */
class RegistryLoader
{
    /**
     * @var string
     */
    protected $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function load(): array
    {
        if (false === $content = file_get_contents($this->filename)) {
            throw new RegistryLoaderException($this->filename);
        }

        return Yaml::parse($content);
    }
}

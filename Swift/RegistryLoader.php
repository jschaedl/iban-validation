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

use Symfony\Component\Yaml\Yaml;

/**
 * Loads the iban_registry text file provided by SWIFT and parses it to yaml.
 *
 * @author Jan Schädlich <mail@janschaedlich.de>
 */
class RegistryLoader
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return array
     */
    public function load()
    {
        return Yaml::parse(file_get_contents($this->filename));
    }
}

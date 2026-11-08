<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Exception;

use Exception;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;

/**
 * Description of CodeLibraryNotFoundException
 *
 * @package Kematjaya\CodeManager\Exception
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeLibraryNotFoundException extends Exception
{
    public function __construct(CodeLibraryClientInterface $client)
    {
        $message = sprintf('code library for class %s not found', get_class($client));

        parent::__construct($message);
    }
}

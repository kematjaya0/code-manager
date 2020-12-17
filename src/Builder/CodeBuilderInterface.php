<?php

namespace Kematjaya\CodeManager\Builder;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeBuilderInterface 
{
    public function generate(string $format, CodeLibraryClientInterface $client, string $separator = CodeLibraryInterface::SEPARATOR_MINUS):string;
}

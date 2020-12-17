<?php

namespace Kematjaya\CodeManager\Manager;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeManagerInterface 
{
    const REGEX_NUMBER = '{number}';
    
    public function generate(CodeLibraryClientInterface $client): CodeLibraryClientInterface;
}

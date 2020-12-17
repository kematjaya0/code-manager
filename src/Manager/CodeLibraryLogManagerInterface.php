<?php

namespace Kematjaya\CodeManager\Manager;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryLogInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeLibraryLogManagerInterface {
    
    public function createLog(CodeLibraryClientInterface $client):CodeLibraryLogInterface;
    
}

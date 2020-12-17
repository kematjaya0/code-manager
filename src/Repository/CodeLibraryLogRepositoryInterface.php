<?php

namespace Kematjaya\CodeManager\Repository;

use Kematjaya\CodeManager\Entity\CodeLibraryLogInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeLibraryLogRepositoryInterface 
{
    public function createLog():CodeLibraryLogInterface;
    
    public function save(CodeLibraryLogInterface $object):void;
}

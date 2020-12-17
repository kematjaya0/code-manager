<?php

namespace Kematjaya\CodeManager\Manager;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryLogInterface;
use Kematjaya\CodeManager\Repository\CodeLibraryLogRepositoryInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeLibraryLogManager implements CodeLibraryLogManagerInterface
{
    /**
     * 
     * @var CodeLibraryLogRepositoryInterface
     */
    private $codeLibraryLogRepo;
    
    public function __construct(CodeLibraryLogRepositoryInterface $codeLibraryLogRepo) 
    {
        $this->codeLibraryLogRepo = $codeLibraryLogRepo;
    }
    
    public function createLog(CodeLibraryClientInterface $client):CodeLibraryLogInterface
    {
        $object = $this->codeLibraryLogRepo->createLog();
        $object->setClassName(get_class($client))
                ->setClassId($client->getClassId())
                ->setCreatedAt(new \DateTime())
                ->setGeneratedCode($client->getGeneratedCode());
        
        $this->codeLibraryLogRepo->save($object);
        
        return $object;
    }
}

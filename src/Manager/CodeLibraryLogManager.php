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
    public function __construct(private readonly CodeLibraryLogRepositoryInterface $codeLibraryLogRepo) {}

    public function createLog(CodeLibraryClientInterface $client): CodeLibraryLogInterface
    {
        $object = $this->codeLibraryLogRepo->createLog();
        $object->setClassName($client::class)
                ->setClassId((string) $client->getClassId())
                ->setCreatedAt(new \DateTime())
                ->setGeneratedCode((string) $client->getGeneratedCode());

        $this->codeLibraryLogRepo->save($object);

        return $object;
    }
}

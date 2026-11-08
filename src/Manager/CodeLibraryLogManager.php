<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Manager;

use DateTime;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryLogInterface;
use Kematjaya\CodeManager\Repository\CodeLibraryLogRepositoryInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeLibraryLogManager implements CodeLibraryLogManagerInterface
{
    public function __construct(
        private CodeLibraryLogRepositoryInterface $codeLibraryLogRepo
    ) {
    }

    public function createLog(CodeLibraryClientInterface $client): CodeLibraryLogInterface
    {
        $object = $this->codeLibraryLogRepo->createLog();
        $object->setClassName(get_class($client))
            ->setClassId($client->getClassId())
            ->setCreatedAt(new DateTime())
            ->setGeneratedCode($client->getGeneratedCode());

        $this->codeLibraryLogRepo->save($object);

        return $object;
    }
}

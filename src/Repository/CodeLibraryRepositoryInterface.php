<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Repository;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeLibraryRepositoryInterface
{
    public function findOneByClient(CodeLibraryClientInterface $client): ?CodeLibraryInterface;

    public function save(CodeLibraryInterface $object): void;
}

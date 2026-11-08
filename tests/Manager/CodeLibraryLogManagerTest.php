<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Tests\Manager;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryLogInterface;
use Kematjaya\CodeManager\Manager\CodeLibraryLogManager;
use Kematjaya\CodeManager\Repository\CodeLibraryLogRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CodeLibraryLogManagerTest extends TestCase
{
    public function testCreateLog(): void
    {
        $log = $this->createMock(CodeLibraryLogInterface::class);
        $log->expects($this->once())->method('setClassName')->willReturnSelf();
        $log->expects($this->once())->method('setClassId')->willReturnSelf();
        $log->expects($this->once())->method('setCreatedAt')->willReturnSelf();
        $log->expects($this->once())->method('setGeneratedCode')->willReturnSelf();

        $repo = $this->createMock(CodeLibraryLogRepositoryInterface::class);
        $repo->expects($this->once())->method('createLog')->willReturn($log);
        $repo->expects($this->once())->method('save')->with($log);

        $client = $this->createMock(CodeLibraryClientInterface::class);
        $client->expects($this->once())->method('getClassId')->willReturn('123');
        $client->expects($this->once())->method('getGeneratedCode')->willReturn('CODE-123');

        $manager = new CodeLibraryLogManager($repo);
        $result = $manager->createLog($client);

        $this->assertSame($log, $result);
    }
}

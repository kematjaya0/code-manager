<?php

namespace Kematjaya\CodeManager\Tests\Manager;

use PHPUnit\Framework\TestCase;
use Kematjaya\CodeManager\Manager\CodeLibraryLogManager;
use Kematjaya\CodeManager\Repository\CodeLibraryLogRepositoryInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryLogInterface;

class CodeLibraryLogManagerTest extends TestCase
{
    public function testCreateLog()
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

    public function testCreateLogWithoutClassId()
    {
        $log = new \Kematjaya\CodeManager\Tests\Model\CodeLibraryLogTest();
        $repo = $this->createConfiguredMock(CodeLibraryLogRepositoryInterface::class, ['createLog' => $log]);
        $client = $this->createConfiguredMock(CodeLibraryClientInterface::class, [
            'getClassId' => null,
            'getGeneratedCode' => 'CODE-1',
        ]);

        $result = (new CodeLibraryLogManager($repo))->createLog($client);

        $this->assertSame('', $result->getClassId());
        $this->assertSame('CODE-1', $result->getGeneratedCode());
    }
}

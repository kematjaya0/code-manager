<?php

namespace Kematjaya\CodeManager\Tests\Manager;

use Kematjaya\CodeManager\Builder\CodeBuilder;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;
use Kematjaya\CodeManager\Exception\NotSupportedResetKeyException;
use Kematjaya\CodeManager\Manager\CodeLibraryLogManagerInterface;
use Kematjaya\CodeManager\Manager\CodeManager;
use Kematjaya\CodeManager\Repository\CodeLibraryRepositoryInterface;
use Kematjaya\CodeManager\Tests\Model\ClientTest;
use Kematjaya\CodeManager\Tests\Model\ConfigurableCodeLibrary;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CodeManagerResetTest extends TestCase
{
    private function createManager(CodeLibraryInterface $library): CodeManager
    {
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library,
        ]);

        return new CodeManager(new CodeBuilder(), $repo, $this->createMock(CodeLibraryLogManagerInterface::class));
    }

    public function testResetKeyOnFirstPosition(): void
    {
        $client = (new ClientTest())->setTest('A');
        $manager = $this->createManager(new ConfigurableCodeLibrary('{test}-{number}', '-', null, '{test}'));

        $this->assertEquals('A-0001', $manager->generate($client)->getGeneratedCode());
        $this->assertEquals('A-0002', $manager->generate($client)->getGeneratedCode());

        $client->setTest('B');
        $this->assertEquals('B-0001', $manager->generate($client)->getGeneratedCode());
    }

    public function testResetKeyWithNonStringLibraryValue(): void
    {
        $client = $this->createConfiguredMock(CodeLibraryClientInterface::class, [
            'getLibrary' => ['year' => 2024],
        ]);
        $codes = [];
        $client->method('setGeneratedCode')->willReturnCallback(function (string $code) use ($client, &$codes): MockObject {
            $codes[] = $code;

            return $client;
        });

        $library = new ConfigurableCodeLibrary('{number}/{year}', '/', 3, '{year}');
        $manager = $this->createManager($library);
        $manager->generate($client);
        $manager->generate($client);

        $this->assertEquals(['001/2024', '002/2024'], $codes);
    }

    public function testNullSeparatorDefaultsToMinus(): void
    {
        $manager = $this->createManager(new ConfigurableCodeLibrary('INV-{number}', null));

        $this->assertEquals('INV-0001', $manager->generate(new ClientTest())->getGeneratedCode());
    }

    public function testCustomLength(): void
    {
        $manager = $this->createManager(new ConfigurableCodeLibrary('{number}.{YYYY}', CodeLibraryInterface::SEPARATOR_DOT, 6));

        $this->assertEquals('000001.' . date('Y'), $manager->generate(new ClientTest())->getGeneratedCode());
    }

    public function testNumberLongerThanLength(): void
    {
        $library = new ConfigurableCodeLibrary('{number}', '-', 2);
        $library->setLastSequence(99);

        $this->assertEquals('100', $this->createManager($library)->generate(new ClientTest())->getGeneratedCode());
    }

    public function testUnknownResetKeyDoesNotReset(): void
    {
        $library = new ConfigurableCodeLibrary('{number}-{unknown}', '-', 4, '{unknown}');
        $manager = $this->createManager($library);

        $this->assertEquals('0001-{unknown}', $manager->generate(new ClientTest())->getGeneratedCode());
        $this->assertEquals('0002-{unknown}', $manager->generate(new ClientTest())->getGeneratedCode());
    }

    public function testLastCodeNotMatchingFormatContinuesSequence(): void
    {
        $client = (new ClientTest())->setTest('A');
        $library = new ConfigurableCodeLibrary('{number}-{test}', '-', 4, '{test}');
        $library->setLastSequence(5)->setLastCode('0005');

        $this->assertEquals('0006-A', $this->createManager($library)->generate($client)->getGeneratedCode());
    }

    public function testResetKeyNotInFormat(): void
    {
        $library = new ConfigurableCodeLibrary('{number}-{YYYY}', '-', 4, '{test}');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("format key '{test}' not found");
        $this->createManager($library)->generate(new ClientTest());
    }

    public function testNotSupportedResetKey(): void
    {
        $library = new ConfigurableCodeLibrary('{number}-{YYYY}', '-', 4, 'YYYY');

        $this->expectException(NotSupportedResetKeyException::class);
        $this->createManager($library)->generate(new ClientTest());
    }
}

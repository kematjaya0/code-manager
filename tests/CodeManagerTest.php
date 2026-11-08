<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Tests;

use Exception;
use Kematjaya\CodeManager\Builder\CodeBuilder;
use Kematjaya\CodeManager\Exception\CodeLibraryNotFoundException;
use Kematjaya\CodeManager\Exception\NotSupportedResetKeyException;
use Kematjaya\CodeManager\Manager\CodeLibraryLogManager;
use Kematjaya\CodeManager\Manager\CodeLibraryLogManagerInterface;
use Kematjaya\CodeManager\Manager\CodeManager;
use Kematjaya\CodeManager\Repository\CodeLibraryLogRepositoryInterface;
use Kematjaya\CodeManager\Repository\CodeLibraryRepositoryInterface;
use Kematjaya\CodeManager\Tests\Model\ClientTest;
use Kematjaya\CodeManager\Tests\Model\CodeLibraryLogTest;
use Kematjaya\CodeManager\Tests\Model\CodeLibraryResetTest;
use Kematjaya\CodeManager\Tests\Model\CodeLibraryTest;
use PHPUnit\Framework\TestCase;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeManagerTest extends TestCase
{
    public function testInstanceManagerLog(): CodeLibraryLogManagerInterface
    {
        $log = new CodeLibraryLogTest();
        $codeLibraryLogRepo = $this->createConfiguredMock(CodeLibraryLogRepositoryInterface::class, [
            'createLog' => $log,
        ]);
        $logManager = new CodeLibraryLogManager($codeLibraryLogRepo);
        $this->assertInstanceOf(CodeLibraryLogManagerInterface::class, $logManager);

        return $logManager;
    }

    /**
     * @depends testInstanceManagerLog
     */
    public function testGenerateException(CodeLibraryLogManagerInterface $logManager): void
    {
        $client = new ClientTest();
        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => null,
        ]);

        $manager = new CodeManager($builder, $repo, $logManager);

        $this->expectException(CodeLibraryNotFoundException::class);
        $manager->generate($client);
    }

    /**
     * @depends testInstanceManagerLog
     */
    public function testGenerateSuccess(CodeLibraryLogManagerInterface $logManager): void
    {
        $client = new ClientTest();
        $library = new CodeLibraryTest();
        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library,
        ]);

        $manager = new CodeManager($builder, $repo, $logManager);

        $arr = [
            ['0001', date('D'), date('M'), date('Y')],
            ['0002', date('D'), date('M'), date('Y')],
        ];
        foreach ($arr as $v) {
            $code = implode('/', $v);
            $this->assertEquals($code, $manager->generate($client)->getGeneratedCode());
        }
    }

    /**
     * @depends testInstanceManagerLog
     */
    public function testGenerateResetSuccess(CodeLibraryLogManagerInterface $logManager): void
    {
        $client = (new ClientTest())->setTest('aa');
        $library = (new CodeLibraryResetTest())->setResetKey('{test}');
        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library,
        ]);

        $manager = new CodeManager($builder, $repo, $logManager);
        $arr = [
            ['0001', date('D'), date('M'), date('Y'), $client->getTest()],
            ['0002', date('D'), date('M'), date('Y'), $client->getTest()],
        ];
        foreach ($arr as $v) {
            $code = implode('/', $v);
            $this->assertEquals($code, $manager->generate($client)->getGeneratedCode());
        }

        $client->setTest('b');
        $arr = [
            ['0001', date('D'), date('M'), date('Y'), $client->getTest()],
            ['0002', date('D'), date('M'), date('Y'), $client->getTest()],
        ];
        foreach ($arr as $v) {
            $code = implode('/', $v);
            $this->assertEquals($code, $manager->generate($client)->getGeneratedCode());
        }
    }

    /**
     * @depends testInstanceManagerLog
     */
    public function testResetKeyAtFirstSegment(CodeLibraryLogManagerInterface $logManager): void
    {
        $client = (new ClientTest())->setTest('grpA');
        $library = (new CodeLibraryResetTest())
            ->setSeparator('-')
            ->setResetKey('{test}')
            ->setFormat('{test}-{number}');

        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library,
        ]);

        $manager = new CodeManager($builder, $repo, $logManager);
        $this->assertEquals('grpA-0001', $manager->generate($client)->getGeneratedCode());
        $this->assertEquals('grpA-0002', $manager->generate($client)->getGeneratedCode());

        $client->setTest('grpB');
        $this->assertEquals('grpB-0001', $manager->generate($client)->getGeneratedCode());
    }

    /**
     * @depends testInstanceManagerLog
     */
    public function testResetWithNullResetKey(CodeLibraryLogManagerInterface $logManager): void
    {
        $client = new ClientTest();
        $library = (new CodeLibraryResetTest())->setResetKey(null);
        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library,
        ]);

        $manager = new CodeManager($builder, $repo, $logManager);
        $result = $manager->generate($client);
        $this->assertNotNull($result->getGeneratedCode());
    }

    /**
     * @depends testInstanceManagerLog
     */
    public function testResetWithUnsupportedResetKey(CodeLibraryLogManagerInterface $logManager): void
    {
        $client = new ClientTest();
        $library = (new CodeLibraryResetTest())->setResetKey('invalid_key_without_braces');
        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library,
        ]);

        $manager = new CodeManager($builder, $repo, $logManager);

        $this->expectException(NotSupportedResetKeyException::class);
        $manager->generate($client);
    }

    /**
     * @depends testInstanceManagerLog
     */
    public function testResetKeyNotFoundInFormat(CodeLibraryLogManagerInterface $logManager): void
    {
        $client = new ClientTest();
        $library = (new CodeLibraryResetTest())
            ->setFormat('{number}-{YYYY}')
            ->setResetKey('{missing_key}');
        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library,
        ]);

        $manager = new CodeManager($builder, $repo, $logManager);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("format key '{missing_key}' not found inside format '{number}-{YYYY}'");
        $manager->generate($client);
    }

    /**
     * @depends testInstanceManagerLog
     */
    public function testResetKeyNotFoundInSplitFormat(CodeLibraryLogManagerInterface $logManager): void
    {
        $client = new ClientTest();
        $library = (new CodeLibraryResetTest())
            ->setFormat('{number}{YYYY}')
            ->setResetKey('{YYYY}')
            ->setLastCode('00012026')
            ->setSeparator('-');
        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library,
        ]);

        $manager = new CodeManager($builder, $repo, $logManager);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('key not found: {YYYY}');
        $manager->generate($client);
    }

    /**
     * @depends testInstanceManagerLog
     */
    public function testNumberLengthGetterAndSetter(CodeLibraryLogManagerInterface $logManager): void
    {
        $client = new ClientTest();
        $library = new CodeLibraryTest();
        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library,
        ]);

        $manager = new CodeManager($builder, $repo, $logManager, 6);
        $this->assertEquals(6, $manager->getNumberLength());

        $manager->setNumberLength(3);
        $this->assertEquals(3, $manager->getNumberLength());

        $this->assertStringStartsWith('001/', (string) $manager->generate($client)->getGeneratedCode());
    }

    /**
     * @depends testInstanceManagerLog
     */
    public function testGenerateWithNullSeparator(CodeLibraryLogManagerInterface $logManager): void
    {
        $client = (new ClientTest())->setTest('val1');
        $library = (new CodeLibraryResetTest())
            ->setSeparator(null)
            ->setResetKey('{test}')
            ->setFormat('{number}-{test}');

        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library,
        ]);

        $manager = new CodeManager($builder, $repo, $logManager);
        $this->assertEquals('0001-val1', $manager->generate($client)->getGeneratedCode());
        $this->assertEquals('0002-val1', $manager->generate($client)->getGeneratedCode());
    }

    /**
     * @depends testInstanceManagerLog
     */
    public function testResetKeyNotInLibrary(CodeLibraryLogManagerInterface $logManager): void
    {
        $client = new ClientTest();
        $library = (new CodeLibraryResetTest())
            ->setSeparator('-')
            ->setResetKey('{missing}')
            ->setFormat('{number}-{missing}')
            ->setLastCode('0001-something');

        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library,
        ]);

        $manager = new CodeManager($builder, $repo, $logManager);
        $this->assertEquals('0001-{missing}', $manager->generate($client)->getGeneratedCode());
    }
}

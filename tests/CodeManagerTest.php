<?php

namespace Kematjaya\CodeManager\Tests;

use PHPUnit\Framework\TestCase;
use Kematjaya\CodeManager\Exception\CodeLibraryNotFoundException;
use Kematjaya\CodeManager\Tests\Model\ClientTest;
use Kematjaya\CodeManager\Tests\Model\CodeLibraryTest;
use Kematjaya\CodeManager\Tests\Model\CodeLibraryResetTest;
use Kematjaya\CodeManager\Tests\Model\CodeLibraryLogTest;
use Kematjaya\CodeManager\Builder\CodeBuilder;
use Kematjaya\CodeManager\Manager\CodeManager;
use Kematjaya\CodeManager\Manager\CodeLibraryLogManager;
use Kematjaya\CodeManager\Manager\CodeLibraryLogManagerInterface;
use Kematjaya\CodeManager\Repository\CodeLibraryRepositoryInterface;
use Kematjaya\CodeManager\Repository\CodeLibraryLogRepositoryInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeManagerTest extends TestCase
{
    /**
     * @depends testInstanceManagerLog
     */
    public function testGenerateException(CodeLibraryLogManagerInterface $logManager)
    {
        $client = new ClientTest();
        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => null
        ]);
        
        $manager = new CodeManager($builder, $repo, $logManager);
        
        $this->expectException(CodeLibraryNotFoundException::class);
        $manager->generate($client);
    }
    
    public function testInstanceManagerLog(): CodeLibraryLogManagerInterface
    {
        $log = new CodeLibraryLogTest();
        $codeLibraryLogRepo = $this->createConfiguredMock(CodeLibraryLogRepositoryInterface::class, [
            'createLog' => $log
        ]);
        $logManager = new CodeLibraryLogManager($codeLibraryLogRepo);
        $this->assertInstanceOf(CodeLibraryLogManagerInterface::class, $logManager);
        
        return $logManager;
    }
    
    /**
     * @depends testInstanceManagerLog
     */
    public function testGenerateSuccess(CodeLibraryLogManagerInterface $logManager)
    {
        $client = new ClientTest();
        $library = new CodeLibraryTest();
        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library
        ]);
        
        $manager = new CodeManager($builder, $repo, $logManager);
        
        $arr = [
            ['0001', date('D'), date('M'), date('Y')],
            ['0002', date('D'), date('M'), date('Y')]
        ];
        foreach ($arr as $v) {
            $code = implode('/', $v);
            $this->assertEquals($code, $manager->generate($client)->getGeneratedCode());
        }
    }
    
    /**
     * @depends testInstanceManagerLog
     */
    public function testGenerateResetSuccess(CodeLibraryLogManagerInterface $logManager)
    {
        $client = new ClientTest();
        $library = new CodeLibraryResetTest();
        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library
        ]);
        
        $manager = new CodeManager($builder, $repo, $logManager);
        
        $arr = [
            ['0001', date('D'), date('M'), date('Y')],
            ['0002', date('D'), date('M'), date('Y')]
        ];
        foreach ($arr as $v) {
            $code = implode('/', $v);
            $this->assertEquals($code, $manager->generate($client)->getGeneratedCode());
        }
    }
}

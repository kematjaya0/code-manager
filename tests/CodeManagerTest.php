<?php

namespace Kematjaya\CodeManager\Tests;

use PHPUnit\Framework\TestCase;
use Kematjaya\CodeManager\Tests\Model\ClientTest;
use Kematjaya\CodeManager\Builder\CodeBuilder;
use Kematjaya\CodeManager\Manager\CodeManager;
use Kematjaya\CodeManager\Repository\CodeLibraryRepositoryInterface;
use Kematjaya\CodeManager\Tests\Model\CodeLibraryTest;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeManagerTest extends TestCase
{
    /*public function testGenerateException()
    {
        $client = new ClientTest();
        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => null
        ]);
        $manager = new CodeManager($builder, $repo);
        
        $this->expectException(\Exception::class);
        $manager->generate($client);
    }
    */
    public function testGenerateSuccess()
    {
        $client = new ClientTest();
        $library = new CodeLibraryTest();
        $builder = new CodeBuilder();
        $repo = $this->createConfiguredMock(CodeLibraryRepositoryInterface::class, [
            'findOneByClient' => $library
        ]);
        $manager = new CodeManager($builder, $repo);
        
        $this->assertEquals('0001/Thu/Dec/2020', $manager->generate($client)->getGeneratedCode());
        $this->assertEquals('0002/Thu/Dec/2020', $manager->generate($client)->getGeneratedCode());
        
    }
}

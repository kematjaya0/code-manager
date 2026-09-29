<?php

namespace Kematjaya\CodeManager\Tests;

use PHPUnit\Framework\TestCase;
use Kematjaya\CodeManager\Builder\AbstractCodeBuilder;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;

class AbstractCodeBuilderTest extends TestCase
{
    public function testGetLibrary()
    {
        $builder = $this->getMockForAbstractClass(AbstractCodeBuilder::class);
        $library = $builder->getLibrary();

        $this->assertIsArray($library);
        $this->assertArrayHasKey('d', $library);
        $this->assertArrayHasKey('YYYY', $library);
        $this->assertArrayHasKey('rand', $library);
    }

    public function testGetFormatValue()
    {
        $builder = $this->getMockForAbstractClass(AbstractCodeBuilder::class);

        $this->assertEquals('test', $builder->getFormatValue('{test}'));
        $this->assertEquals('DD', $builder->getFormatValue('{DD}'));
    }

    public function testIsSupported()
    {
        $builder = $this->getMockForAbstractClass(AbstractCodeBuilder::class);

        $this->assertTrue($builder->isSupported('{test}'));
        $this->assertFalse($builder->isSupported('test'));
        $this->assertFalse($builder->isSupported('{test'));
        $this->assertFalse($builder->isSupported('test}'));
    }
}

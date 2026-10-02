<?php

namespace Kematjaya\CodeManager\Tests;

use Kematjaya\CodeManager\Builder\AbstractCodeBuilder;
use PHPUnit\Framework\TestCase;

class AbstractCodeBuilderTest extends TestCase
{
    public function testGetLibrary(): void
    {
        $builder = $this->getMockForAbstractClass(AbstractCodeBuilder::class);
        $library = $builder->getLibrary();

        $this->assertIsArray($library);
        $this->assertArrayHasKey('d', $library);
        $this->assertArrayHasKey('YYYY', $library);
        $this->assertArrayHasKey('rand', $library);
    }

    public function testGetFormatValue(): void
    {
        $builder = $this->getMockForAbstractClass(AbstractCodeBuilder::class);

        $this->assertEquals('test', $builder->getFormatValue('{test}'));
        $this->assertEquals('DD', $builder->getFormatValue('{DD}'));
    }

    public function testIsSupported(): void
    {
        $builder = $this->getMockForAbstractClass(AbstractCodeBuilder::class);

        $this->assertTrue($builder->isSupported('{test}'));
        $this->assertFalse($builder->isSupported('test'));
        $this->assertFalse($builder->isSupported('{test'));
        $this->assertFalse($builder->isSupported('test}'));
    }
}

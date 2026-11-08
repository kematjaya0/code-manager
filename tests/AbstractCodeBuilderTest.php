<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Tests;

use Kematjaya\CodeManager\Builder\AbstractCodeBuilder;
use PHPUnit\Framework\TestCase;

class AbstractCodeBuilderTest extends TestCase
{
    public function testGetLibrary(): void
    {
        $builder = $this->getMockForAbstractClass(AbstractCodeBuilder::class);
        $library = $builder->getLibrary();

        $this->assertNotEmpty($library);
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

    public function testSeparateFormat(): void
    {
        $builder = $this->getMockForAbstractClass(AbstractCodeBuilder::class);
        $reflection = new \ReflectionMethod(AbstractCodeBuilder::class, 'separateFormat');

        $this->assertEquals(['a', 'b', 'c'], $reflection->invoke($builder, 'a-b-c', '-'));
        $this->assertEquals(['a-b-c'], $reflection->invoke($builder, 'a-b-c', ''));
    }
}

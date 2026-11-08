<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Tests;

use Kematjaya\CodeManager\Builder\CodeBuilder;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;
use Kematjaya\CodeManager\Tests\Model\ClientTest;
use PHPUnit\Framework\TestCase;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeBuilderTest extends TestCase
{
    public function testGenerate(): void
    {
        $client = new ClientTest();
        $builder = new CodeBuilder();

        $result = [
            '{number}', date('D'), date('M'), date('Y'),
        ];

        $this->assertEquals(
            implode('-', $result),
            $builder->generate('{number}-{DD}-{MM}-{YYYY}', $client)
        );
        $this->assertEquals(
            implode(CodeLibraryInterface::SEPARATOR_SLASH, $result),
            $builder->generate(
                '{number}/{DD}/{MM}/{YYYY}',
                $client,
                CodeLibraryInterface::SEPARATOR_SLASH
            )
        );
    }

    public function testGetLibrary(): void
    {
        $builder = new CodeBuilder();
        $library = $builder->getLibrary();

        $this->assertNotEmpty($library);
        $this->assertArrayHasKey('DD', $library);
        $this->assertArrayHasKey('MM', $library);
        $this->assertArrayHasKey('dd', $library);
        $this->assertArrayHasKey('mm', $library);
        $this->assertArrayHasKey('yy', $library);
        $this->assertArrayHasKey('yyyy', $library);
        $this->assertArrayHasKey('YYYY', $library);
        $this->assertArrayHasKey('d', $library);
        $this->assertArrayHasKey('rand', $library);
    }

    public function testGenerateWithUnsupportedAndUnknownPlaceholders(): void
    {
        $client = (new ClientTest())->setTest('mytest');
        $builder = new CodeBuilder();

        $format = 'INV-{test}-{unknown_key}';
        $generated = $builder->generate($format, $client);

        $this->assertEquals('INV-mytest-{unknown_key}', $generated);
    }
}

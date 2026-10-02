<?php

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

        $this->assertEquals(implode('-', $result), $builder->generate('{number}-{DD}-{MM}-{YYYY}', $client));
        $this->assertEquals(implode(CodeLibraryInterface::SEPARATOR_SLASH, $result), $builder->generate('{number}/{DD}/{MM}/{YYYY}', $client, CodeLibraryInterface::SEPARATOR_SLASH));
    }
}

<?php

namespace Kematjaya\CodeManager\Tests;

use PHPUnit\Framework\TestCase;
use Kematjaya\CodeManager\Tests\Model\ClientTest;
use Kematjaya\CodeManager\Builder\CodeBuilder;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeBuilderTest extends TestCase
{
    public function testGenerate()
    {
        $client = new ClientTest();
        $builder = new CodeBuilder();
        
        $result = [
            '{number}', date('D'), date('M'), date('Y')
        ];
        
        $this->assertEquals(implode('-', $result), $builder->generate('{number}-{DD}-{MM}-{YY}', $client));
        $this->assertEquals(implode(CodeLibraryInterface::SEPARATOR_SLASH, $result), $builder->generate('{number}/{DD}/{MM}/{YY}', $client, CodeLibraryInterface::SEPARATOR_SLASH));
    }
}

<?php

namespace Kematjaya\CodeManager\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Kematjaya\CodeManager\Exception\CodeLibraryNotFoundException;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;

class CodeLibraryNotFoundExceptionTest extends TestCase
{
    public function testExceptionMessage()
    {
        $client = $this->createMock(CodeLibraryClientInterface::class);

        $exception = new CodeLibraryNotFoundException($client);

        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertStringContainsString('code library for class', $exception->getMessage());
        $this->assertStringContainsString(get_class($client), $exception->getMessage());
    }
}

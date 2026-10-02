<?php

namespace Kematjaya\CodeManager\Tests\Exception;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Exception\CodeLibraryNotFoundException;
use PHPUnit\Framework\TestCase;

class CodeLibraryNotFoundExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $client = $this->createMock(CodeLibraryClientInterface::class);

        $exception = new CodeLibraryNotFoundException($client);

        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertStringContainsString('code library for class', $exception->getMessage());
        $this->assertStringContainsString($client::class, $exception->getMessage());
    }
}

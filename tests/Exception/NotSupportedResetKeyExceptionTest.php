<?php

namespace Kematjaya\CodeManager\Tests\Exception;

use Kematjaya\CodeManager\Entity\CodeLibraryResetInterface;
use Kematjaya\CodeManager\Exception\NotSupportedResetKeyException;
use PHPUnit\Framework\TestCase;

class NotSupportedResetKeyExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $class = $this->createMock(CodeLibraryResetInterface::class);

        $exception = new NotSupportedResetKeyException($class);

        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertStringContainsString('reset key  format not supported for class:', $exception->getMessage());
        $this->assertStringContainsString($class::class, $exception->getMessage());
        $this->assertStringContainsString('{key}', $exception->getMessage());
    }
}

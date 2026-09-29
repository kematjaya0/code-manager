<?php

namespace Kematjaya\CodeManager\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Kematjaya\CodeManager\Exception\NotSupportedResetKeyException;
use Kematjaya\CodeManager\Entity\CodeLibraryResetInterface;

class NotSupportedResetKeyExceptionTest extends TestCase
{
    public function testExceptionMessage()
    {
        $class = $this->createMock(CodeLibraryResetInterface::class);

        $exception = new NotSupportedResetKeyException($class);

        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertStringContainsString('reset key  format not supported for class:', $exception->getMessage());
        $this->assertStringContainsString(get_class($class), $exception->getMessage());
        $this->assertStringContainsString('{key}', $exception->getMessage());
    }
}

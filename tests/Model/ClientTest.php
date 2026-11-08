<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Tests\Model;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class ClientTest implements CodeLibraryClientInterface
{
    private ?string $code = null;
    private ?string $test = null;
    private ?string $classId = null;

    public function getClassId(): ?string
    {
        return $this->classId ?? (string) rand(1, 10);
    }

    public function setClassId(?string $classId): self
    {
        $this->classId = $classId;

        return $this;
    }

    public function getGeneratedCode(): ?string
    {
        return $this->code;
    }

    public function getTest(): ?string
    {
        return $this->test;
    }

    public function setTest(string $test): self
    {
        $this->test = $test;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getLibrary(): array
    {
        return [
            'test' => $this->getTest(),
        ];
    }

    public function setGeneratedCode(string $code): CodeLibraryClientInterface
    {
        $this->code = $code;

        return $this;
    }
}

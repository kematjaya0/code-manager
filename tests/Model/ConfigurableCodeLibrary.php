<?php

namespace Kematjaya\CodeManager\Tests\Model;

use Kematjaya\CodeManager\Entity\CodeLibraryInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryResetInterface;

/**
 * Code library yang format, separator, length dan reset key-nya bisa diatur dari test
 */
class ConfigurableCodeLibrary implements CodeLibraryResetInterface
{
    private ?\DateTimeInterface $lastUsed = null;

    private ?int $lastSequence = null;

    private ?string $lastCode = null;

    public function __construct(
        private readonly string $format,
        private readonly ?string $separator,
        private readonly ?int $length = null,
        private readonly ?string $resetKey = null,
    ) {}

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function getClassName(): ?string
    {
        return ClientTest::class;
    }

    public function getSeparator(): ?string
    {
        return $this->separator;
    }

    public function setLastUsed(\DateTimeInterface $lastUsed): CodeLibraryInterface
    {
        $this->lastUsed = $lastUsed;

        return $this;
    }

    public function getLastUsed(): ?\DateTimeInterface
    {
        return $this->lastUsed;
    }

    public function setLastSequence(int $number): CodeLibraryInterface
    {
        $this->lastSequence = $number;

        return $this;
    }

    public function getLastSequence(): ?int
    {
        return $this->lastSequence;
    }

    public function setLastCode(string $code): CodeLibraryInterface
    {
        $this->lastCode = $code;

        return $this;
    }

    public function getLastCode(): ?string
    {
        return $this->lastCode;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function getResetKey(): ?string
    {
        return $this->resetKey;
    }
}

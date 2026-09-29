<?php

namespace Kematjaya\CodeManager\Tests\Model;

use Kematjaya\CodeManager\Entity\CodeLibraryInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryResetInterface;

/**
 * Code library yang format, separator, length dan reset key-nya bisa diatur dari test
 */
class ConfigurableCodeLibrary implements CodeLibraryResetInterface
{
    private $format;

    private $separator;

    private $length;

    private $resetKey;

    private $lastUsed;

    private $lastSequence;

    private $lastCode;

    public function __construct(string $format, ?string $separator, ?int $length = null, ?string $resetKey = null)
    {
        $this->format = $format;
        $this->separator = $separator;
        $this->length = $length;
        $this->resetKey = $resetKey;
    }

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

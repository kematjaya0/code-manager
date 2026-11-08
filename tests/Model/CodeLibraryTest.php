<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Tests\Model;

use DateTimeInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeLibraryTest implements CodeLibraryInterface
{
    private ?DateTimeInterface $lastUsed = null;
    private ?int $lastSequence = null;
    private ?string $code = null;
    protected ?string $format = null;
    private ?string $separator = self::SEPARATOR_SLASH;

    public function setFormat(?string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getFormat(): ?string
    {
        if ($this->format !== null) {
            return $this->format;
        }

        $arr = [
            '{number}', '{DD}', '{MM}', '{YYYY}',
        ];

        return implode($this->getSeparator() ?? self::SEPARATOR_SLASH, $arr);
    }

    public function getClassName(): ?string
    {
        return ClientTest::class;
    }

    public function setLastUsed(DateTimeInterface $lastUsed): static
    {
        $this->lastUsed = $lastUsed;

        return $this;
    }

    public function getLastUsed(): ?DateTimeInterface
    {
        return $this->lastUsed;
    }

    public function setLastSequence(int $number): static
    {
        $this->lastSequence = $number;

        return $this;
    }

    public function getLastSequence(): ?int
    {
        return $this->lastSequence;
    }

    public function setLastCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getLastCode(): ?string
    {
        return $this->code;
    }

    public function setSeparator(?string $separator): static
    {
        $this->separator = $separator;

        return $this;
    }

    public function getSeparator(): ?string
    {
        return $this->separator;
    }
}

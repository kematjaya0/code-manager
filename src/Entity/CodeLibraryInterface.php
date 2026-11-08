<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Entity;

use DateTimeInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeLibraryInterface
{
    public const SEPARATOR_SLASH = '/';
    public const SEPARATOR_BACKSLASH = '\\';
    public const SEPARATOR_MINUS = '-';

    public function getFormat(): ?string;

    public function getClassName(): ?string;

    public function getSeparator(): ?string;

    public function setLastUsed(DateTimeInterface $lastUsed): self;

    public function getLastUsed(): ?DateTimeInterface;

    public function setLastSequence(int $number): self;

    public function getLastSequence(): ?int;

    public function setLastCode(string $code): self;

    public function getLastCode(): ?string;
}

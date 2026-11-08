<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Entity;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeLibraryClientInterface
{
    public function getClassId(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function getLibrary(): array;

    public function getGeneratedCode(): ?string;

    public function setGeneratedCode(string $code): self;
}

<?php

namespace Kematjaya\CodeManager\Tests\Model;

use Kematjaya\CodeManager\Entity\CodeLibraryLogInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeLibraryLogTest implements CodeLibraryLogInterface
{
    private ?string $classId = null;

    private ?string $className = null;

    private ?\DateTimeInterface $createdAt = null;

    private ?string $code = null;

    public function getClassId(): ?string
    {
        return $this->classId;
    }

    public function getClassName(): ?string
    {
        return $this->className;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getGeneratedCode(): ?string
    {
        return $this->code;
    }

    public function setClassId(string $classId): CodeLibraryLogInterface
    {
        $this->classId = $classId;

        return $this;
    }

    public function setClassName(string $className): CodeLibraryLogInterface
    {
        $this->className = $className;

        return $this;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): CodeLibraryLogInterface
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setGeneratedCode(string $code): CodeLibraryLogInterface
    {
        $this->code = $code;

        return $this;
    }
}

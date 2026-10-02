<?php

namespace Kematjaya\CodeManager\Tests\Model;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class ClientTest implements CodeLibraryClientInterface
{
    private ?string $code = null;

    private ?string $test = null;

    public function getClassId(): ?string
    {
        return random_int(1, 10);
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

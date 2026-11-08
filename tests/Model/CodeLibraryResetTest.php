<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Tests\Model;

use Kematjaya\CodeManager\Entity\CodeLibraryResetInterface;

/**
 * Description of CodeLibraryResetTest
 *
 * @package Kematjaya\CodeManager\Tests\Model
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeLibraryResetTest extends CodeLibraryTest implements CodeLibraryResetInterface
{
    private ?string $resetKey = null;

    public function getResetKey(): ?string
    {
        return $this->resetKey;
    }

    public function setResetKey(?string $resetKey): self
    {
        $this->resetKey = $resetKey;

        return $this;
    }

    public function getFormat(): ?string
    {
        if ($this->format !== null) {
            return $this->format;
        }

        $arr = [
            '{number}', '{DD}', '{MM}', '{YYYY}', '{test}',
        ];

        return implode($this->getSeparator() ?? self::SEPARATOR_SLASH, $arr);
    }
}

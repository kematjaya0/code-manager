<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Entity;

/**
 * Description of CodeLibraryResetInterface
 *
 * @package Kematjaya\CodeManager\Entity
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeLibraryResetInterface extends CodeLibraryInterface
{
    public function getResetKey(): ?string;
}

<?php

/**
 * This file is part of the code-manager.
 */

namespace Kematjaya\CodeManager\Entity;

/**
 * @package Kematjaya\CodeManager\Entity
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeManagerClientInterface extends CodeLibraryClientInterface
{
    public function getClientClassName():string;
    
    public function getAdditionalConditions():array;
}

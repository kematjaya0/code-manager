<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
    //put your code here
    public function getResetKey(): ?string 
    {
        return '{MM}';
    }

}

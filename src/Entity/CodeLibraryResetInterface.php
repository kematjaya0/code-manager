<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kematjaya\CodeManager\Entity;

/**
 * Description of CodeLibraryResetInterface
 *
 * @package Kematjaya\CodeManager\Entity
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeLibraryResetInterface extends CodeLibraryInterface
{
    public function getResetKey():?string;
}

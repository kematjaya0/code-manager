<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kematjaya\CodeManager\Exception;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Exception;

/**
 * Description of CodeLibraryNotFoundException
 *
 * @package Kematjaya\CodeManager\Exception
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeLibraryNotFoundException extends Exception
{
    public function __construct(CodeLibraryClientInterface $client) 
    {
        
        $message = sprintf("code library for class %s not found", get_class($client));
        
        parent::__construct($message);
    }
}

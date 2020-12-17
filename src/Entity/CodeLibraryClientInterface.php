<?php

namespace Kematjaya\CodeManager\Entity;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeLibraryClientInterface 
{
    public function getClassId():?string;
    
    public function getLibrary():array;
    
    public function getGeneratedCode():?string;
    
    public function setGeneratedCode(string $code):self;
}

<?php

namespace Kematjaya\CodeManager\Entity;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeLibraryInterface 
{
    const SEPARATOR_SLASH = '/';
    const SEPARATOR_BACKSLASH = '\\';
    const SEPARATOR_MINUS = '-';
    const SEPARATOR_DOT = '.';
    
    public function getFormat():?string;
    
    public function getClassName():?string;
    
    public function getSeparator():?string;
    
    public function setLastUsed(\DateTimeInterface $lastUsed):self;
    
    public function getLastUsed():?\DateTimeInterface;
    
    public function setLastSequence(int $number):self;
    
    public function getLastSequence():?int;
    
    public function setLastCode(string $code):self;
    
    public function getLastCode():?string;
    
    public function getLength():?int;
}

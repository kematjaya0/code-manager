<?php

namespace Kematjaya\CodeManager\Entity;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeLibraryLogInterface 
{
    public function setCreatedAt(\DateTimeInterface $createdAt):self;
    
    public function getCreatedAt():?\DateTimeInterface;
    
    public function setClassName(string $className):self;
    
    public function getClassName():?string;
    
    public function setClassId(string $classId):self;
    
    public function getClassId():?string;
    
    public function setGeneratedCode(string $code):self;
    
    public function getGeneratedCode():?string;
}

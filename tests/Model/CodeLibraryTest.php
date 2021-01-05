<?php

namespace Kematjaya\CodeManager\Tests\Model;

use Kematjaya\CodeManager\Entity\CodeLibraryInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeLibraryTest implements CodeLibraryInterface
{
    
    /**
     * 
     * @var \DateTimeInterface
     */
    private $lastUsed;
    
    /**
     * 
     * @var int
     */
    private $lastSequence;
    /**
     * 
     * @var string
     */
    private $code;
    
    public function getFormat():?string
    {
        $arr = [
            '{number}', '{DD}', '{MM}', '{YYYY}'
        ];
        
        return implode($this->getSeparator(), $arr);
    }
    
    public function getClassName():?string
    {
        return ClientTest::class;
    }
    
    public function setLastUsed(\DateTimeInterface $lastUsed):CodeLibraryInterface
    {
        $this->lastUsed = $lastUsed;
        
        return $this;
    }
    
    public function getLastUsed():\DateTimeInterface
    {
        return $this->lastUsed;
    }
    
    public function setLastSequence(int $number):CodeLibraryInterface
    {
        $this->lastSequence = $number;
        
        return $this;
    }
    
    public function getLastSequence():?int
    {
        return $this->lastSequence;
    }
    
    public function setLastCode(string $code):CodeLibraryInterface
    {
        $this->code = $code;
        
        return $this;
    }
    
    public function getLastCode():?string
    {
        return $this->code;
    }

    public function getSeparator(): ?string 
    {
        return self::SEPARATOR_SLASH;
    }

}

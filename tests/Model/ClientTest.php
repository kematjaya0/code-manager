<?php

namespace Kematjaya\CodeManager\Tests\Model;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class ClientTest implements CodeLibraryClientInterface
{
    /**
     * 
     * @var string
     */
    private $code;
    
    public function getClassId(): ?string 
    {
        return rand(1, 10);
    }

    public function getGeneratedCode(): ?string 
    {
        return $this->code;
    }

    public function getLibrary(): array 
    {
        return [
            'DD' => date("D"),
            'MM' => date("M"),
            'YY' => date("Y"),
        ];
    }

    public function setGeneratedCode(string $code): CodeLibraryClientInterface 
    {
        $this->code = $code;
        
        return $this;
    }

}

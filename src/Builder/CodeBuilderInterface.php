<?php

namespace Kematjaya\CodeManager\Builder;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeBuilderInterface 
{
    
    const BRACE_START = '{';
    const BRACE_END = '}';
    /**
     * Generate code by format
     *  
     * @param string $format
     * @param CodeLibraryClientInterface $client
     * @param string $separator
     * @return string
     */
    public function generate(string $format, CodeLibraryClientInterface $client, string $separator = CodeLibraryInterface::SEPARATOR_MINUS):string;
    
    /**
     * Library of code
     * 
     * @return array
     */
    public function getLibrary():array;
}

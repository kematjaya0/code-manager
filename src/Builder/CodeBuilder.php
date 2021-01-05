<?php

namespace Kematjaya\CodeManager\Builder;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeBuilder extends AbstractCodeBuilder
{
    
    public function generate(string $format, CodeLibraryClientInterface $client, string $separator = CodeLibraryInterface::SEPARATOR_MINUS): string 
    {
        $resultSets = [];
        $library = array_merge($this->getLibrary(), $client->getLibrary());
        foreach ($this->separateFormat($format, $separator) as $value) {
            $process = $this->isSupported($value);
            if (!$process) {
                continue;
            }
            
            $val = $this->getFormatValue($value);
            if (isset($library[$val])) {
                $resultSets[] = $library[$val];
                continue;
            }
            
            $resultSets[] = $value;
        }
        
        return implode($separator, $resultSets);
    }

    public function getLibrary():array
    {
        $arr = [
            'DD' => date("D"),
            'MM' => date("M"),
            'dd' => date('d'),
            'mm' => date('m'),
            'yy' => date('y'),
            'yyyy' => date('Y')
        ];
        
        return array_merge($arr, parent::getLibrary());
    }
}

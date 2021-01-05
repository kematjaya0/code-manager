<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kematjaya\CodeManager\Builder;

/**
 * Description of AbstractCodeBuilder
 *
 * @package Kematjaya\CodeManager\Builder
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
abstract class AbstractCodeBuilder implements CodeBuilderInterface
{
    /**
     * Library of code
     * 
     * @return array
     */
    public function getLibrary():array
    {
        return [
            'd' => date('d'),
            'm' => date('m'),
            'DD' => date("D"),
            'MM' => date("M"),
            'YYYY' => date("Y"),
            'YY' => date('y')
        ];
    }
    
    public function isSupported(string $value):bool
    {
        return preg_match('/^' . self::BRACE_START . '/', $value) and preg_match('/' . self::BRACE_END . '/', $value);
    }
    
    protected function separateFormat(string $format, string $separator):array
    {
        return explode($separator, $format);
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kematjaya\CodeManager\Exception;

use Exception;
use Kematjaya\CodeManager\Entity\CodeLibraryResetInterface;
use Kematjaya\CodeManager\Builder\CodeBuilderInterface;
/**
 * Description of NullResetKeyException
 *
 * @package Kematjaya\CodeManager\Exception
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class NotSupportedResetKeyException extends Exception
{
    public function __construct(CodeLibraryResetInterface $class)
    {
        $message = sprintf("reset key  format not supported for class: %s, use %s%s%s", get_class($class), CodeBuilderInterface::BRACE_START, 'key', CodeBuilderInterface::BRACE_END);
        parent::__construct($message);
    }
}

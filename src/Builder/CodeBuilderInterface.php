<?php

namespace Kematjaya\CodeManager\Builder;

use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface CodeBuilderInterface
{
    public const BRACE_START = '{';
    public const BRACE_END = '}';
    /**
     * Generate code by format
     */
    public function generate(string $format, CodeLibraryClientInterface $client, string $separator = CodeLibraryInterface::SEPARATOR_MINUS): string;

    /**
     * Library of code
     */
    public function getLibrary(): array;
}

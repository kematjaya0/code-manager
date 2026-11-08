<?php

declare(strict_types=1);

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
     * @return array<string, mixed>
     */
    public function getLibrary(): array
    {
        return [
            'd' => date('d'),
            'm' => date('m'),
            'DD' => date('D'),
            'MM' => date('M'),
            'YYYY' => date('Y'),
            'YY' => date('y'),
            'HH' => date('H'),
            'ii' => date('i'),
            'ss' => date('s'),
            'rand' => rand(),
        ];
    }

    public function getFormatValue(string $format): ?string
    {
        return str_replace(self::BRACE_END, '', str_replace(self::BRACE_START, '', $format));
    }

    public function isSupported(string $value): bool
    {
        return str_starts_with($value, self::BRACE_START) && str_ends_with($value, self::BRACE_END);
    }

    /**
     * @return list<string>
     */
    protected function separateFormat(string $format, string $separator): array
    {
        if ($separator === '') {
            return [$format];
        }

        return explode($separator, $format);
    }
}

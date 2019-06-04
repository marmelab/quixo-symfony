<?php

// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Utils\GameUtils;

class AppExtension extends AbstractExtension
{
    /**
     * getFilters
     *
     * @return void
     */
    public function getFilters()
    {
        return [
            new TwigFilter('getSymbol', [$this, 'getSymbol']),
        ];
    }

    /**
     * getSymbol
     *
     * @param  int $value
     *
     * @return string
     */
    public function getSymbol(int $value): string
    {
        if ($value === GameUtils::CIRCLE_TEAM) {
            return 'circle';
        }
        if ($value === GameUtils::CROSS_TEAM) {
            return 'cross';
        }
        return 'neutral';
    }
}

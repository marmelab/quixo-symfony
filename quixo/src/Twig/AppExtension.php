<?php

// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Entity\Cube;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('getSymbol', [$this, 'getSymbol']),
        ];
    }

    public function getSymbol($value)
    {
        if ($value == Cube::CIRCLE_TEAM) {
            return 'circle';
        }
        if ($value == Cube::CROSS_TEAM) {
            return 'cross';
        }
        return 'neutral';
    }
}

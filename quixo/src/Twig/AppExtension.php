<?php

// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Entity\Game;

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
        if ($value == Game::CIRCLE_TEAM) {
            return 'circle';
        }
        if ($value == Game::CROSS_TEAM) {
            return 'cross';
        }
        return 'neutral';
    }
}

<?php

namespace App\Module\AnimeTitle\Enum;

enum AgeRating: string
{
    case G = 'g';
    case PG = 'pg';
    case PG_13 = 'pg13';
    case R_17 = 'r17';
    case R = 'r';
    case Rx = 'rx';
}
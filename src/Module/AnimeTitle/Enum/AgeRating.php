<?php

namespace App\Module\AnimeTitle\Enum;

enum AgeRating: string
{
    case G = 'G';
    case PG = 'PG';
    case PG_13 = 'PG-13';
    case R = 'R';
    case NC_17 = 'NC-17';
}
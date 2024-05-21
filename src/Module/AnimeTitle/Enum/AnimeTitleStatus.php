<?php

namespace App\Module\AnimeTitle\Enum;

enum AnimeTitleStatus: string
{
    case RELEASED = 'Released';
    case ONGOING = 'Ongoing';
    case ANNOUNCED = 'Announced';
}
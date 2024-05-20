<?php

namespace App\Enum;

enum AnimeTitleStatus: string
{
    case RELEASED = 'Released';
    case ONGOING = 'Ongoing';
    case ANNOUNCED = 'Announced';
}
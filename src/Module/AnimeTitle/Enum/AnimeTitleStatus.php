<?php

namespace App\Module\AnimeTitle\Enum;

enum AnimeTitleStatus: string
{
    case FINISHED_AIRING = 'finished airing'; // Завершился показ
    case CURRENT = 'currently airing'; // В данный момент идет
    case UPCOMING = 'upcoming'; // Ожидается
    case NOT_YET_RELEASED = 'not yet released'; // Еще не вышло
    case TO_BE_ANNOUNCED = 'to be announced'; // Дата выхода не объявлена
    case UNKNOWN = 'unknown';
}

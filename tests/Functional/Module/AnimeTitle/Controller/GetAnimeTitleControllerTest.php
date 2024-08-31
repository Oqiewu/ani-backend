<?php

namespace App\Tests\Functional\Module\AnimeTitle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Module\AnimeTitle\Service\AnimeTitleService;
use App\Module\AnimeTitle\Entity\AnimeTitle;
use App\Module\AnimeTitle\Enum\AnimeTitleType;
use App\Module\AnimeTitle\Enum\AnimeTitleStatus;
use App\Module\AnimeTitle\Enum\AgeRating;
use Symfony\Component\HttpFoundation\Response;

class GetAnimeTitleControllerTest extends WebTestCase {

    public function testGetAnimeTitle() 
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $animeTitleServiceMock = $this->createMock(AnimeTitleService::class);

        $animeTitle = new AnimeTitle();
        $animeTitle->setName("Название аниме")
                   ->setOriginalName("Оригинальное название аниме")
                   ->setDescription("Описание аниме")
                   ->setGenres(["action"])
                   ->setType(AnimeTitleType::MOVIE)
                   ->setStatus(AnimeTitleStatus::CURRENT)
                   ->setReleaseDate(new \DateTime("2022-05-20"))
                   ->setAgeRating(AgeRating::PG_13)
                   ->setImageUrl("http://example.com/image.jpg")
                   ->setSmallImageUrl("http://example.com/small.jpg")
                   ->setLargeImageUrl("http://example.com/large.jpg");

        $animeTitleServiceMock->expects($this->once())
        ->method('getOneById')
        ->willReturn($animeTitle);

        $container->set(AnimeTitleService::class, $animeTitleServiceMock);

        $client->request('GET', '/anime_title/1');

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        restore_exception_handler();
    }
}
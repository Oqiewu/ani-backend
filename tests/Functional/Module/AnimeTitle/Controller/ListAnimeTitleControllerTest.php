<?php

namespace App\Tests\Functional\Module\AnimeTitle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Module\AnimeTitle\Service\AnimeTitleService;
use App\Module\AnimeTitle\Entity\AnimeTitle;
use App\Module\AnimeTitle\Enum\AnimeTitleType;
use App\Module\AnimeTitle\Enum\AnimeTitleStatus;
use App\Module\AnimeTitle\Enum\AgeRating;

class ListAnimeTitleControllerTest extends WebTestCase
{
    public function testGetAnimeTitleList()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $animeTitleServiceMock = $this->createMock(AnimeTitleService::class);

        $animeTitle1 = (new AnimeTitle())
            ->setName("Название аниме 1")
            ->setOriginalName("Оригинальное название аниме 1")
            ->setDescription("Описание аниме 1")
            ->setGenres(["action"])
            ->setType(AnimeTitleType::MOVIE)
            ->setStatus(AnimeTitleStatus::CURRENT)
            ->setReleaseDate(new \DateTime("2022-05-20"))
            ->setAgeRating(AgeRating::PG_13)
            ->setImageUrl("http://example.com/image1.jpg");

        $animeTitle2 = (new AnimeTitle())
            ->setName("Название аниме 2")
            ->setOriginalName("Оригинальное название аниме 2")
            ->setDescription("Описание аниме 2")
            ->setGenres(["adventure"])
            ->setType(AnimeTitleType::TV)
            ->setStatus(AnimeTitleStatus::FINISHED_AIRING)
            ->setReleaseDate(new \DateTime("2021-08-15"))
            ->setAgeRating(AgeRating::R)
            ->setImageUrl("http://example.com/image2.jpg");

        $animeTitles = [$animeTitle1, $animeTitle2];

        $animeTitleServiceMock->expects($this->once())
            ->method('getPaginated')
            ->willReturn($animeTitles);

        $animeTitleServiceMock->expects($this->once())
            ->method('countAll')
            ->willReturn(2);

        $container->set(AnimeTitleService::class, $animeTitleServiceMock);

        $client->request('GET', '/anime_title');

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $expectedResponse = [
            'currentPage' => 1,
            'totalPages' => 1,
            'totalItems' => 2,
            'items' => [
                [
                    'id' => null,
                    'name' => 'Название аниме 1',
                    'originalName' => 'Оригинальное название аниме 1',
                    'description' => 'Описание аниме 1',
                    'genres' => ['action'],
                    'type' => 'movie',
                    'status' => 'currently airing',
                    'releaseDate' => '2022-05-20T00:00:00+00:00',
                    'ageRating' => 'pg13',
                    'imageUrl' => 'http://example.com/image1.jpg',
                    'smallImageUrl' => null,
                    'largeImageUrl' => null,
                ],
                [
                    'id' => null,
                    'name' => 'Название аниме 2',
                    'originalName' => 'Оригинальное название аниме 2',
                    'description' => 'Описание аниме 2',
                    'genres' => ['adventure'],
                    'type' => 'tv',
                    'status' => 'finished airing',
                    'releaseDate' => '2021-08-15T00:00:00+00:00',
                    'ageRating' => 'r',
                    'imageUrl' => 'http://example.com/image2.jpg',
                    'smallImageUrl' => null,
                    'largeImageUrl' => null,
                ],
            ],
        ];

        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedResponse),
            $response->getContent()
        );

        restore_exception_handler();
    }

    public function testGetAnimeTitleListWithNameFilter()
    {
        $client = static::createClient();
        $container = $client->getContainer();
    
        $animeTitleServiceMock = $this->createMock(AnimeTitleService::class);
    
        $animeTitle = new AnimeTitle();
        $animeTitle->setName("Naruto")
                   ->setOriginalName("ナルト")
                   ->setDescription("Ниндзя приключения")
                   ->setGenres(["action", "adventure"])
                   ->setType(AnimeTitleType::TV)
                   ->setStatus(AnimeTitleStatus::FINISHED_AIRING)
                   ->setReleaseDate(new \DateTime("2002-10-03"))
                   ->setAgeRating(AgeRating::PG_13)
                   ->setImageUrl("http://example.com/naruto.jpg");
    
        $animeTitles = [$animeTitle];
    
        $animeTitleServiceMock->expects($this->once())
            ->method('getPaginated')
            ->willReturn($animeTitles);
    
        $animeTitleServiceMock->expects($this->once())
            ->method('countAll')
            ->willReturn(1);
    
        $container->set(AnimeTitleService::class, $animeTitleServiceMock);
    
        $client->request('GET', '/anime_title', ['name' => 'Naruto']);
    
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    
        $expectedResponse = [
            'currentPage' => 1,
            'totalPages' => 1,
            'totalItems' => 1,
            'items' => [
                [
                    'id' => null,
                    'name' => 'Naruto',
                    'originalName' => 'ナルト',
                    'description' => 'Ниндзя приключения',
                    'genres' => ['action', 'adventure'],
                    'type' => 'tv',
                    'status' => 'finished airing',
                    'releaseDate' => '2002-10-03T00:00:00+00:00',
                    'ageRating' => 'pg13',
                    'imageUrl' => 'http://example.com/naruto.jpg',
                    'smallImageUrl' => null,
                    'largeImageUrl' => null,
                ],
            ],
        ];
    
        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedResponse),
            $response->getContent()
        );
    
        restore_exception_handler();
    }

    public function testGetAnimeTitleListWithNoResults()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $animeTitleServiceMock = $this->createMock(AnimeTitleService::class);

        $animeTitleServiceMock->expects($this->once())
            ->method('getPaginated')
            ->willReturn([]);

        $animeTitleServiceMock->expects($this->once())
            ->method('countAll')
            ->willReturn(0);

        $container->set(AnimeTitleService::class, $animeTitleServiceMock);

        $client->request('GET', '/anime_title', ['name' => 'NonExistentTitle']);

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'currentPage' => 1,
                'totalPages' => 0,
                'totalItems' => 0,
                'items' => [],
            ]),
            $response->getContent()
        );

        restore_exception_handler();
    }
}

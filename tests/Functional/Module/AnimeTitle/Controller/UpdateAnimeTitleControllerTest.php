<?php

namespace App\Tests\Functional\Module\AnimeTitle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Module\AnimeTitle\Service\AnimeTitleService;
use App\Module\AnimeTitle\Entity\AnimeTitle;
use App\Module\AnimeTitle\Enum\AnimeTitleType;
use App\Module\AnimeTitle\Enum\AnimeTitleStatus;
use App\Module\AnimeTitle\Enum\AgeRating;

class UpdateAnimeTitleControllerTest extends WebTestCase {
    public function testUpdateAnimeTitle()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $animeTitleServiceMock = $this->createMock(AnimeTitleService::class);
        
        $animeTitle = new AnimeTitle();
        $animeTitle->setName("Название аниме")
                   ->setOriginalName("Оригинальное название аниме")
                   ->setDescription("Изменённое описание аниме")
                   ->setGenres(["action"])
                   ->setType(AnimeTitleType::MOVIE)
                   ->setStatus(AnimeTitleStatus::CURRENT)
                   ->setReleaseDate(new \DateTime("2022-05-20"))
                   ->setAgeRating(AgeRating::PG_13)
                   ->setImageUrl("http://example.com/image.jpg")
                   ->setSmallImageUrl("http://example.com/small.jpg")
                   ->setLargeImageUrl("http://example.com/large.jpg");

        $animeTitleServiceMock->expects($this->once())
            ->method('update')
            ->willReturn($animeTitle);

        $container->set(AnimeTitleService::class, $animeTitleServiceMock);

        $data = [
            'id' => null,
            "name" => "Название аниме",
            "originalName" => "Оригинальное название аниме",
            "description" => "Изменённое описание аниме",
            "genres" => ["action"],
            "type" => "movie",
            "status" => "currently airing",
            "releaseDate" => "2022-05-20T00:00:00+00:00",
            "ageRating" => "pg13",
            "imageUrl" => "http://example.com/image.jpg",
            "smallImageUrl" => "http://example.com/small.jpg",
            "largeImageUrl" => "http://example.com/large.jpg"
        ];

        $client->request('PUT', '/anime_title/1', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($data));

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Anime title updated', 'data' => $data]),
            $response->getContent()
        );

        restore_exception_handler();
    }
}
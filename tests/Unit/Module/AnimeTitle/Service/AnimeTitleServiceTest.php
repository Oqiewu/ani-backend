<?php

namespace App\Tests\Unit\Module\AnimeTitle\Service;

use App\Module\AnimeTitle\Service\AnimeTitleService;
use App\Module\AnimeTitle\Repository\AnimeTitleRepository;
use App\Module\AnimeTitle\Entity\AnimeTitle;
use App\Module\AnimeTitle\DTO\AnimeTitleDTO;
use App\Module\AnimeTitle\Enum\AnimeTitleGenre;
use App\Module\AnimeTitle\Enum\AnimeTitleType;
use App\Module\AnimeTitle\Enum\AnimeTitleStatus;
use App\Module\AnimeTitle\Enum\AgeRating;
use PHPUnit\Framework\TestCase;

class AnimeTitleServiceTest extends TestCase {
    private $animeTitleRepository;
    private $animeTitleService;

    protected function setUp(): void
    {
        $this->animeTitleRepository = $this->createMock(AnimeTitleRepository::class);
        $this->animeTitleService = new AnimeTitleService($this->animeTitleRepository);
    }

    public function testCreate()
    {
        $animeTitleDTO = new AnimeTitleDTO();
        $animeTitleDTO->setName('Naruto')
                      ->setOriginalName('ナルト')
                      ->setDescription('A ninja story')
                      ->setType(AnimeTitleType::TV)
                      ->setStatus(AnimeTitleStatus::FINISHED_AIRING)
                      ->setReleaseDate(new \DateTime('2002-10-03'))
                      ->setAgeRating(AgeRating::PG_13)
                      ->setGenres([AnimeTitleGenre::ACTION, AnimeTitleGenre::ADVENTURE]);

        $this->animeTitleRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(AnimeTitle::class), true);
        $animeTitle = $this->animeTitleService->create($animeTitleDTO);

        $this->assertInstanceOf(AnimeTitle::class, $animeTitle);

        $this->assertEquals('Naruto', $animeTitle->getName());
        $this->assertEquals('ナルト', $animeTitle->getOriginalName());
        $this->assertEquals('A ninja story', $animeTitle->getDescription());
        $this->assertEquals(AnimeTitleType::TV, $animeTitle->getType());
        $this->assertEquals(AnimeTitleStatus::FINISHED_AIRING, $animeTitle->getStatus());
        $this->assertEquals(new \DateTime('2002-10-03'), $animeTitle->getReleaseDate());
        $this->assertEquals(AgeRating::PG_13, $animeTitle->getAgeRating());
        $this->assertEquals([AnimeTitleGenre::ACTION->value, AnimeTitleGenre::ADVENTURE->value], $animeTitle->getGenres());
    }

    public function testUpdate()
    {
        $animeTitle = new AnimeTitle();
        $this->animeTitleRepository->method('findById')->willReturn($animeTitle);

        $animeTitleDTO = new AnimeTitleDTO();
        $animeTitleDTO->setName('Naruto Shippuden')
                      ->setOriginalName('ナルト疾風伝')
                      ->setDescription('A continuation of the ninja story')
                      ->setType(AnimeTitleType::TV)
                      ->setStatus(AnimeTitleStatus::CURRENT)
                      ->setReleaseDate(new \DateTime('2007-02-15'))
                      ->setAgeRating(AgeRating::PG_13)
                      ->setGenres([AnimeTitleGenre::ACTION, AnimeTitleGenre::ADVENTURE]);

        $this->animeTitleRepository->expects($this->once())
            ->method('save')
            ->with($animeTitle, true);

        $updatedAnimeTitle = $this->animeTitleService->update(1, $animeTitleDTO);

        $this->assertInstanceOf(AnimeTitle::class, $updatedAnimeTitle);
        $this->assertEquals('Naruto Shippuden', $updatedAnimeTitle->getName());
        $this->assertEquals('ナルト疾風伝', $updatedAnimeTitle->getOriginalName());
        $this->assertEquals('A continuation of the ninja story', $updatedAnimeTitle->getDescription());
        $this->assertEquals(AnimeTitleType::TV, $updatedAnimeTitle->getType());
        $this->assertEquals(AnimeTitleStatus::CURRENT, $updatedAnimeTitle->getStatus());
        $this->assertEquals(new \DateTime('2007-02-15'), $updatedAnimeTitle->getReleaseDate());
        $this->assertEquals(AgeRating::PG_13, $updatedAnimeTitle->getAgeRating());
        $this->assertEquals([AnimeTitleGenre::ACTION->value, AnimeTitleGenre::ADVENTURE->value], $updatedAnimeTitle->getGenres());
    }

    public function testGetOneById()
    {
        $animeTitle = new AnimeTitle();
        $this->animeTitleRepository->method('findById')->willReturn($animeTitle);

        $result = $this->animeTitleService->getOneById(1);

        $this->assertSame($animeTitle, $result);
    }
    

    public function testDelete()
    {
        $animeTitle = new AnimeTitle();
        $this->animeTitleRepository->method('findById')->willReturn($animeTitle);

        $this->animeTitleRepository->expects($this->once())
            ->method('remove')
            ->with($animeTitle, true);

        $result = $this->animeTitleService->delete(1);

        $this->assertEquals('AnimeTitle with ID 1 has been successfully deleted.', $result);
    }


    public function testGetPaginated()
    {
        $this->animeTitleRepository->method('findPaginated')
            ->willReturn(['Naruto', 'One Piece']);

        $result = $this->animeTitleService->getPaginated(1, 10);

        $this->assertCount(2, $result);
        $this->assertEquals('Naruto', $result[0]);
        $this->assertEquals('One Piece', $result[1]);
    }

    public function testCountAll()
    {
        $this->animeTitleRepository->method('countAll')->willReturn(100);

        $result = $this->animeTitleService->countAll();

        $this->assertEquals(100, $result);
    }
}
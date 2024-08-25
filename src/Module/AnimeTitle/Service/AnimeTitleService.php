<?php

namespace App\Module\AnimeTitle\Service;

use App\Module\AnimeTitle\Entity\AnimeTitle;
use App\Module\AnimeTitle\Repository\AnimeTitleRepository;
use App\Module\AnimeTitle\DTO\AnimeTitleDTO;

class AnimeTitleService 
{
    public function __construct(
        private AnimeTitleRepository $animeTitleRepository,
    ) {}

    public function create(AnimeTitleDTO $animeTitleData): AnimeTitle
    {
        $animeTitle = new AnimeTitle();
        $animeTitle
            ->setName($animeTitleData->getName())
            ->setOriginalName($animeTitleData->getOriginalName())
            ->setDescription($animeTitleData->getDescription())
            ->setType($animeTitleData->getType())
            ->setStatus($animeTitleData->getStatus())
            ->setReleaseDate($animeTitleData->getReleaseDate())
            ->setAgeRating($animeTitleData->getAgeRating());
    
        foreach ($animeTitleData->getGenres() as $genre) {
            $animeTitle->addGenre($genre);
        }
    
        $this->animeTitleRepository->save($animeTitle, true);
    
        return $animeTitle;
    }

    public function update(int $id, AnimeTitleDTO $animeTitleData): AnimeTitle
    {
        $animeTitle = $this->animeTitleRepository->findById($id);
    
        if (!$animeTitle) {
            throw new \Exception("Anime title with id $id not found");
        }
    
        $animeTitle
            ->setName($animeTitleData->getName())
            ->setOriginalName($animeTitleData->getOriginalName())
            ->setDescription($animeTitleData->getDescription())
            ->setType($animeTitleData->getType())
            ->setStatus($animeTitleData->getStatus())
            ->setReleaseDate($animeTitleData->getReleaseDate())
            ->setAgeRating($animeTitleData->getAgeRating());
    
        $animeTitle->setGenres([]);
        foreach ($animeTitleData->getGenres() as $genre) {
            $animeTitle->addGenre($genre);
        }
    
        $this->animeTitleRepository->save($animeTitle, true);
    
        return $animeTitle;
    }


    public function getOneById(int $id): AnimeTitle
    {
        $animeTitle = $this->animeTitleRepository->findById($id);
        
        if (!$animeTitle) {
            throw new \Exception("Anime title with id $id not found");
        }

        return $animeTitle;
    }

    public function delete(int $id): string
    {
        $animeTitle = $this->animeTitleRepository->findById($id);

        if (!$animeTitle) {
            throw new \Exception("AnimeTitle with ID $id not found.");
        }

        $this->animeTitleRepository->remove($animeTitle, true);
    
        return "AnimeTitle with ID $id has been successfully deleted.";
    }

    public function getPaginated(int $page, int $limit, string $name = ''): array
    {
        return $this->animeTitleRepository->findPaginated($page, $limit, $name);
    }
    
    public function countAll(string $name = ''): int
    {
        return $this->animeTitleRepository->countAll($name);
    }
}

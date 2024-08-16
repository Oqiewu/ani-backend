<?php

namespace App\Module\AnimeTitle\Service;

use App\Module\AnimeTitle\Entity\AnimeTitle;
use App\Module\AnimeTitle\Enum\AnimeTitleGenre;
use App\Module\AnimeTitle\Enum\AnimeTitleType;
use App\Module\AnimeTitle\Enum\AnimeTitleStatus;
use App\Module\AnimeTitle\Enum\AgeRating;
use App\Module\AnimeTitle\Repository\AnimeTitleRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JikanApiService
{
    private HttpClientInterface $client;

    public function __construct(
        private AnimeTitleRepository $animeTitleRepository
    ) {
        $this->client = HttpClient::create();
    }

    public function fetchAnimeTitles(int $page = 1): array
    {
        $response = $this->client->request('GET', 'https://api.jikan.moe/v4/anime', [
            'query' => [
                'page' => $page,
                'limit' => 25,
            ]
        ]);

        return $response->toArray()['data'];
    }

    public function createOrUpdateAnimeTitleFromData(array $animeData): AnimeTitle
    {
        $canonicalTitle = $animeData['title'];
        $existingAnimeTitle = $this->animeTitleRepository->findOneBy(['name' => $canonicalTitle]);

        if ($existingAnimeTitle) {
            // Обновление существующего объекта
            $this->updateAnimeTitle($existingAnimeTitle, $animeData);
            return $existingAnimeTitle;
        }

        // Создание нового объекта
        $animeTitle = new AnimeTitle();
        $this->updateAnimeTitle($animeTitle, $animeData);
        return $animeTitle;
    }

    private function updateAnimeTitle(AnimeTitle $animeTitle, array $animeData): void
    {
        $animeTitle
            ->setName($animeData['title'])
            ->setOriginalName($animeData['title_english'] ?? '')
            ->setDescription($animeData['synopsis'] ?? '')
            ->setGenres($this->mapGenres($animeData['genres'] ?? []))
            ->setType($this->mapType($animeData['type']))
            ->setStatus($this->mapStatus($animeData['status']))
            ->setReleaseDate(new \DateTime($animeData['aired']['from'] ?? ''))
            ->setAgeRating($this->mapAgeRating($animeData['rating'] ?? ''));
    }

    private function mapGenres(array $genres): array
    {
        $genreMapping = [
            'Action' => AnimeTitleGenre::ACTION,
            'Adventure' => AnimeTitleGenre::ADVENTURE,
            'Comedy' => AnimeTitleGenre::COMEDY,
            'Drama' => AnimeTitleGenre::DRAMA,
            'Fantasy' => AnimeTitleGenre::FANTASY,
            'Horror' => AnimeTitleGenre::HORROR,
            'Mystery' => AnimeTitleGenre::MYSTERY,
            'Romance' => AnimeTitleGenre::ROMANCE,
            'Sci-Fi' => AnimeTitleGenre::SCI_FI,
            'Slice of Life' => AnimeTitleGenre::SLICE_OF_LIFE,
            'Sports' => AnimeTitleGenre::SPORTS,
            'Supernatural' => AnimeTitleGenre::SUPERNATURAL,
            'Thriller' => AnimeTitleGenre::THRILLER,
            'Mecha' => AnimeTitleGenre::MECHA,
            'Music' => AnimeTitleGenre::MUSIC,
            'Psychological' => AnimeTitleGenre::PSYCHOLOGICAL,
            'Historical' => AnimeTitleGenre::HISTORICAL,
            'Josei' => AnimeTitleGenre::JOSEI,
            'Shoujo' => AnimeTitleGenre::SHOUJO,
            'Shounen' => AnimeTitleGenre::SHOUNEN,
            'Seinen' => AnimeTitleGenre::SEINEN,
            'Isekai' => AnimeTitleGenre::ISEKAI,
            'Dementia' => AnimeTitleGenre::DEMENTIA,
            'Demons' => AnimeTitleGenre::DEMONS,
            'Fantasy Slice of Life' => AnimeTitleGenre::FANTASY_SLICE_OF_LIFE,
            'Gender Bender' => AnimeTitleGenre::GENDER_BENDER,
            'Gore' => AnimeTitleGenre::GORE,
            'Harem' => AnimeTitleGenre::HAREM,
            'Historical Drama' => AnimeTitleGenre::HISTORICAL_DRAMA,
            'Kids' => AnimeTitleGenre::KIDS,
            'Magic' => AnimeTitleGenre::MAGIC,
            'Martial Arts' => AnimeTitleGenre::MARTIAL_ARTS,
            'Military' => AnimeTitleGenre::MILITARY,
            'Parody' => AnimeTitleGenre::PARODY,
            'Police' => AnimeTitleGenre::POLICE,
            'Post-Apocalyptic' => AnimeTitleGenre::POST_APOCALYPTIC,
            'Samurai' => AnimeTitleGenre::SAMURAI,
            'School' => AnimeTitleGenre::SCHOOL,
            'Shounen Ai' => AnimeTitleGenre::SHOUNEN_AI,
            'Shoujo Ai' => AnimeTitleGenre::SHOUJO_AI,
            'Space' => AnimeTitleGenre::SPACE,
            'Super Power' => AnimeTitleGenre::SUPER_POWER,
            'Suspense' => AnimeTitleGenre::SUSPENSE,
            'Vampire' => AnimeTitleGenre::VAMPIRE,
        ];

        return array_map(
            fn($genre) => $genreMapping[$genre['name']] ?? AnimeTitleGenre::UNKNOWN,
            $genres
        );
    }

    private function mapType(?string $type): AnimeTitleType
    {
        return AnimeTitleType::tryFrom(strtolower($type)) ?? AnimeTitleType::UNKNOWN;
    }
    
    private function mapStatus(?string $status): AnimeTitleStatus
    {
        return AnimeTitleStatus::tryFrom(strtolower($status)) ?? AnimeTitleStatus::UNKNOWN;
    }
    

    private function mapAgeRating(?string $ageRating): AgeRating
    {
        if ($ageRating === null) {
            return AgeRating::UNKNOWN;
        }
    
        $rating = strtolower($ageRating);
    
        return match (true) {
            str_contains($rating, 'g') => AgeRating::G,
            str_contains($rating, 'pg') => AgeRating::PG,
            str_contains($rating, 'pg13') => AgeRating::PG_13,
            str_contains($rating, 'r17') || str_contains($rating, 'r - 17+') => AgeRating::R_17,
            str_contains($rating, 'r+') => AgeRating::R_PLUS,
            str_contains($rating, 'rx') => AgeRating::RX,
            default => AgeRating::UNKNOWN,
        };
    }
    
}

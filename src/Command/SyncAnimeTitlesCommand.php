<?php

namespace App\Command;

use App\Module\AnimeTitle\Entity\AnimeTitle;
use App\Module\AnimeTitle\Enum\AnimeTitleGenre;
use App\Module\AnimeTitle\Enum\AnimeTitleType;
use App\Module\AnimeTitle\Enum\AnimeTitleStatus;
use App\Module\AnimeTitle\Enum\AgeRating;
use App\Module\AnimeTitle\Repository\AnimeTitleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:sync-anime-titles',
    description: 'Synchronizes anime titles from cache with the database.'
)]
class SyncAnimeTitlesCommand extends Command
{
    private const BATCH_SIZE = 1000;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private AnimeTitleRepository $animeTitleRepository,
        private string $cacheDir
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();
        $cacheFile = $this->cacheDir . '/anime_titles_cache.json';

        if (!$filesystem->exists($cacheFile)) {
            $output->writeln('Cache file not found. Please run the caching command first.');
            return Command::FAILURE;
        }

        $cacheContent = file_get_contents($cacheFile);
        $animeTitles = json_decode($cacheContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $output->writeln('Error decoding JSON from cache file: ' . json_last_error_msg());
            return Command::FAILURE;
        }

        if (empty($animeTitles)) {
            $output->writeln('No anime titles found in the cache.');
            return Command::FAILURE;
        }

        $totalProcessed = 0;
        try {
            foreach ($animeTitles as $anime) {
                $totalProcessed++;

                $animeTitle = $this->createOrUpdateAnimeTitleFromData($anime);

                $this->entityManager->persist($animeTitle);

                if ($totalProcessed % self::BATCH_SIZE === 0) {
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                }

                $output->writeln("Processed anime $totalProcessed: " . ($anime['title'] ?? 'Unknown'));
            }

            $this->entityManager->flush();
            $this->entityManager->clear();
        } catch (\Exception $e) {
            $output->writeln("Error processing anime: " . $e->getMessage());
            return Command::FAILURE;
        }

        $output->writeln('Anime titles synchronized successfully.');

        return Command::SUCCESS;
    }

    private function createOrUpdateAnimeTitleFromData(array $animeData): AnimeTitle
    {
        $canonicalTitle = $animeData['title'];
        $existingAnimeTitle = $this->animeTitleRepository->findOneBy(['name' => $canonicalTitle]);

        if ($existingAnimeTitle) {
            $this->updateAnimeTitle($existingAnimeTitle, $animeData);
            return $existingAnimeTitle;
        }

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
            ->setImageUrl($animeData['images']['jpg']['image_url'])
            ->setSmallImageUrl($animeData['images']['jpg']['small_image_url'])
            ->setLargeImageUrl($animeData['images']['jpg']['large_image_url'])
            ->setGenres($this->mapGenres($animeData['genres'] ?? []))
            ->setType($this->mapType($animeData['type']))
            ->setStatus($this->mapStatus($animeData['status']))
            ->setReleaseDate(new \DateTime($animeData['aired']['from'] ?? ''))
            ->setAgeRating($this->mapAgeRating($animeData['rating'] ?? ''))
            ->setRank($animeData['rank'] ?? null);
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

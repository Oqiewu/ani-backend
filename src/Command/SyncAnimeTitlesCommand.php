<?php

namespace App\Command;

use App\Module\AnimeTitle\Service\JikanApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Module\AnimeTitle\Entity\AnimeTitle;

#[AsCommand(
    name: 'app:sync-anime-titles',
    description: 'Synchronizes anime titles from cache with the database.'
)]
class SyncAnimeTitlesCommand extends Command
{
    private const BATCH_SIZE = 1000;

    public function __construct(
        private JikanApiService $jikanApiService,
        private EntityManagerInterface $entityManager,
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

                // Create or update the AnimeTitle entity
                $animeTitle = $this->jikanApiService->createOrUpdateAnimeTitleFromData($anime);

                // Persist the entity
                $this->entityManager->persist($animeTitle);

                // Flush and clear the EntityManager periodically
                if ($totalProcessed % self::BATCH_SIZE === 0) {
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                }

                $output->writeln("Processed anime $totalProcessed: " . ($anime['title'] ?? 'Unknown'));
            }

            // Final flush and clear after the loop
            $this->entityManager->flush();
            $this->entityManager->clear();
        } catch (\Exception $e) {
            $output->writeln("Error processing anime: " . $e->getMessage());
            return Command::FAILURE;
        }

        $output->writeln('Anime titles synchronized successfully.');

        return Command::SUCCESS;
    }
}

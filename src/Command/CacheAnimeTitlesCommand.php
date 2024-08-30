<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:cache-anime-titles',
    description: 'Fetches anime titles from Jikan API and saves them to a JSON file.'
)]
class CacheAnimeTitlesCommand extends Command
{
    private HttpClientInterface $client;

    public function __construct(
        private string $cacheDir,
        private string $jikanApiUrl
    ) {
        parent::__construct();
        $this->client = HttpClient::create();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $page = 1;
        $animeTitles = [];
        $filesystem = new Filesystem();

        do {
            $output->writeln("Fetching page $page...");
            sleep(1);
            $response = $this->client->request('GET', $this->jikanApiUrl . '/anime', [
                'query' => [
                    'page' => $page,
                    'limit' => 25,
                ]
            ]);

            $animeData = $response->toArray()['data'] ?? [];

            if (empty($animeData)) {
                $output->writeln('No more anime titles found.');
                break;
            }

            $output->writeln("Fetched " . count($animeData) . " anime titles from page $page.");
            $animeTitles = array_merge($animeTitles, $animeData);
            $page++;
        } while (!empty($animeData));

        $cacheFile = $this->cacheDir . '/anime_titles_cache.json';
        $filesystem->dumpFile($cacheFile, json_encode($animeTitles, JSON_PRETTY_PRINT));

        $output->writeln('Anime titles cached successfully.');

        return Command::SUCCESS;
    }
}

<?php

namespace App\Module\AnimeTitle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Module\AnimeTitle\Service\AnimeTitleService;

class ListAnimeTitleController extends AbstractController
{
    public function __construct(
        private AnimeTitleService $animeTitleService
    ) {}

    #[Route('/anime_title', name: 'get_anime_title_list', methods: ['GET'])]
    public function get(): Response
    {
        $listAnimeTitle = $this->animeTitleService->getAll();
        return $this->json($listAnimeTitle, Response::HTTP_OK);
    }
}

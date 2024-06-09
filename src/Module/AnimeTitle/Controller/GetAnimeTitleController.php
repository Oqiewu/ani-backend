<?php

namespace App\Module\AnimeTitle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Module\AnimeTitle\Service\AnimeTitleService;

class GetAnimeTitleController extends AbstractController
{
    public function __construct(
        private AnimeTitleService $animeTitleService
    ) {}

    #[Route('/anime_title/{id}', name: 'get_anime_title_by_id', methods: ['GET'])]
    public function get(int $id): Response
    {
        $animeTitle = $this->animeTitleService->getOneById($id);
        return $this->json($animeTitle, Response::HTTP_OK);
    }
}

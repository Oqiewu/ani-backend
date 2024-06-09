<?php

namespace App\Module\AnimeTitle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Module\AnimeTitle\Service\AnimeTitleService;

class DeleteAnimeTitleController extends AbstractController
{
    public function __construct(
        private AnimeTitleService $animeTitleService
    ) {}

    #[Route('/anime_title/{id}', name: 'delete_anime_title', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $this->animeTitleService->delete($id);
        return $this->json(['message' => 'Anime title deleted'], Response::HTTP_OK);
    }
}

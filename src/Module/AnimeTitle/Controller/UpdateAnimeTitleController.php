<?php

namespace App\Module\AnimeTitle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Module\AnimeTitle\Service\AnimeTitleService;
use App\Module\AnimeTitle\DTO\AnimeTitleDTO;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class UpdateAnimeTitleController extends AbstractController
{
    public function __construct(
        private AnimeTitleService $animeTitleService
    ) {}

    #[Route('/anime_title/{id}', name: 'update_anime_title', methods: ['PUT'])]
    public function update(
        int $id, 
        #[MapRequestPayload] AnimeTitleDTO $animeTitleDTO
    ): Response {
        $updatedAnimeTitle = $this->animeTitleService->update($id, $animeTitleDTO);
        return $this->json(['message' => 'Anime title updated', 'data' => $updatedAnimeTitle], Response::HTTP_OK);
    }
}

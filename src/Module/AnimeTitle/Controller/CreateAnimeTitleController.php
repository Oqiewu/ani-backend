<?php

namespace App\Module\AnimeTitle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Module\AnimeTitle\Service\AnimeTitleService;
use App\Module\AnimeTitle\DTO\AnimeTitleDTO;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class CreateAnimeTitleController extends AbstractController
{
    public function __construct(
        private AnimeTitleService $animeTitleService
    ) {}

    #[Route('/anime_title', name: 'create_anime_title', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] AnimeTitleDTO $animeTitleDTO
    ): Response {
        $this->animeTitleService->create($animeTitleDTO);
        return $this->json(['message' => 'Anime title created'], Response::HTTP_CREATED);
    }
}

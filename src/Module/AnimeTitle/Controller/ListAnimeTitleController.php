<?php

namespace App\Module\AnimeTitle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Module\AnimeTitle\Service\AnimeTitleService;

class ListAnimeTitleController extends AbstractController
{
    public function __construct(
        private AnimeTitleService $animeTitleService
    ) {}

    #[Route('/anime_title', name: 'get_anime_title_list', methods: ['GET'])]
    public function get(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 20);

        $listAnimeTitle = $this->animeTitleService->getPaginated($page, $limit);
        $totalItems = $this->animeTitleService->countAll();

        return $this->json([
            'currentPage' => $page,
            'totalPages' => (int) ceil($totalItems / $limit),
            'totalItems' => $totalItems,
            'items' => $listAnimeTitle,
        ], Response::HTTP_OK);
    }
}

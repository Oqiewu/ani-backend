<?php

namespace App\Module\AnimeTitle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Module\AnimeTitle\Service\AnimeTitleService;
use App\Module\AnimeTitle\DTO\AnimeTitleDTO;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateAnimeTitleController extends AbstractController
{
    public function __construct(
        private AnimeTitleService $animeTitleService,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    #[Route('/anime_title/{id}', name: 'update_anime_title', methods: ['PUT'])]
    public function update(int $id, Request $request): Response
    {
        $data = $request->getContent();
        $animeTitleDTO = $this->serializer->deserialize($data, AnimeTitleDTO::class, 'json');

        $errors = $this->validator->validate($animeTitleDTO);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        try {
            $updatedAnimeTitle = $this->animeTitleService->update($id, $animeTitleDTO);
            return $this->json(['message' => 'Anime title updated', 'data' => $updatedAnimeTitle], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

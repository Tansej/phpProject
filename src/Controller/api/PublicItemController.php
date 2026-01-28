<?php

namespace App\Controller\api;

use App\Entity\PublicItem;
use App\Repository\PublicItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

// List and show routes, full CRUD for Subject in SubjectController
final class PublicItemController extends AbstractController
{
    #[Route('/api/public/item', name: 'app_api_public_item', methods: ['GET'])]
    public function index(PublicItemRepository $publicItemRepository): JsonResponse
    {
        return $this->json($publicItemRepository->findAll());
    }

    #[Route('/api/public/item/{id}', name: 'app_api_public_item_show', methods: ['GET'])]
    public function show(PublicItem $publicItem): JsonResponse
    {
        return $this->json($publicItem);
    }
}

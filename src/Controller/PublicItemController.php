<?php

namespace App\Controller;

use App\Entity\PublicItem;
use App\Form\PublicItemType;
use App\Repository\PublicItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/public/item')]
final class PublicItemController extends AbstractController
{
    #[Route(name: 'app_public_item_index', methods: ['GET'])]
    public function index(PublicItemRepository $publicItemRepository): Response
    {
        return $this->render('public_item/index.html.twig', [
            'public_items' => $publicItemRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_public_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publicItem = new PublicItem();
        $form = $this->createForm(PublicItemType::class, $publicItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publicItem);
            $entityManager->flush();

            return $this->redirectToRoute('app_public_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('public_item/new.html.twig', [
            'public_item' => $publicItem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_public_item_show', methods: ['GET'])]
    public function show(PublicItem $publicItem): Response
    {
        return $this->render('public_item/show.html.twig', [
            'public_item' => $publicItem,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_public_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PublicItem $publicItem, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicItemType::class, $publicItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_public_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('public_item/edit.html.twig', [
            'public_item' => $publicItem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_public_item_delete', methods: ['POST'])]
    public function delete(Request $request, PublicItem $publicItem, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publicItem->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($publicItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_public_item_index', [], Response::HTTP_SEE_OTHER);
    }
}

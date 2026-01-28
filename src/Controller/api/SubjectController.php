<?php

namespace App\Controller\api;

use App\Entity\Subject;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

// Full CRUD REST routes
final class SubjectController extends AbstractController
{
    #[Route('/api/subject', name: 'app_api_subject', methods: ['GET'])]
    public function index(SubjectRepository $subjectRepository): JsonResponse
    {
        return $this->json($subjectRepository->findAll());
    }

    #[Route('/api/subject/{id}', name: 'app_api_subject_show', methods: ['GET'])]
    public function show(Subject $subject): JsonResponse
    {
        return $this->json($subject);
    }

    #[Route('/api/subject/new', name: 'app_api_subject_create', methods: ['POST'])]
    public function save(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $content = $request->getContent();
        $subject = $serializer->deserialize($content, Subject::class, 'json');

        $errors = $validator->validate($subject);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $entityManager->persist($subject);
        $entityManager->flush();

        return $this->json($subject, 201);
    }

    #[Route('/api/subject/{id}', name: 'app_api_subject_update', methods: ['PUT', 'PATCH'])]
    public function update(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, Subject $subject): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Subject::class, 'json', ['object_to_populate' => $subject]);

        $errors = $validator->validate($subject);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $entityManager->flush();

        return $this->json($subject, 201);
    }

    #[Route('/api/subject/{id}', name: 'app_api_subject_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Subject $subject): JsonResponse
    {
        $entityManager->remove($subject);
        $entityManager->flush();
        return $this->json("Subject deleted!");
    }
}

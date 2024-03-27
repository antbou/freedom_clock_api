<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Model\User\UsersDTO;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/users', name: 'api_users_', format: 'json')]
final class UsersController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
    ) {
    }

    #[Route(name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json', serializationContext: [
            'groups' => ['user:write']
        ])] User $user
    ): JsonResponse {
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json(data: $user, status: Response::HTTP_CREATED, context: ['groups' => ['user:read']]);
    }

    #[Route(name: 'list', methods: ['GET'])]
    public function list(
        #[MapRequestPayload(acceptFormat: 'json')] UsersDTO $usersDto
    ): JsonResponse {
        $users = $this->userRepository->findByIds(
            page: $usersDto->pagination?->page,
            limit: $usersDto->pagination?->limit,
            ids: $usersDto->ids
        );

        return $this->json(data: $users, status: Response::HTTP_OK, context: ['groups' => ['user:read']]);
    }
}

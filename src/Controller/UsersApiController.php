<?php

namespace App\Controller;

use App\Entity\User;
use App\Model\CreateUserDTO;
use App\Model\UsersDTO;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/users', name: 'api_users_', format: 'json')]
final class UsersApiController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository
    ) {
    }

    #[Route(name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] CreateUserDTO $userDto
    ): JsonResponse {
        $user = $this->createUser($userDto);

        $violations = $this->validator->validate($user);
        if (count($violations) > 0) {
            return $this->json($violations, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json($user, Response::HTTP_CREATED);
    }

    #[Route(name: 'list', methods: ['GET'])]
    public function list(
        #[MapRequestPayload(acceptFormat: 'json')] UsersDTO $usersDto
    ): JsonResponse {
        $users = $this->userRepository->findByIdsAndPagination(
            page: $usersDto->pagination?->page,
            limit: $usersDto->pagination?->limit,
            ids: $usersDto->ids
        );

        return $this->json($users, Response::HTTP_OK);
    }

    private function createUser(CreateUserDTO $userDto): User
    {
        $user = new User();
        $user->setUsername($userDto->username);
        $user->setPassword($this->passwordHasher->hashPassword($user, $userDto->password));

        return $user;
    }
}

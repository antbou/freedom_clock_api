<?php

namespace App\Controller\Api;

use App\Factory\UserFactory;
use App\Model\User\UsersDTO;
use App\Model\User\CreateUserDTO;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/api/users', name: 'api_users_', format: 'json')]
final class UsersController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    #[Route(name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] CreateUserDTO $userDto
    ): JsonResponse {

        $attributes = $this->normalizer->normalize($userDto);

        $user = UserFactory::new($attributes)->withoutPersisting()->create();

        $violations = $this->validator->validate($user->object());
        if (count($violations) > 0) {
            return $this->json($violations, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->save();

        return $this->json(data: $user->object(), status: Response::HTTP_CREATED, context: ['groups' => ['user:read']]);
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

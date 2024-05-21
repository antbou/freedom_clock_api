<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\View\PaginatorView;
use App\Model\User\UsersDTO;
use OpenApi\Attributes as OA;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/users', name: 'api_users_', format: 'json')]
#[OA\Tag(name: 'users')]
final class UsersController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
    ) {
    }

    #[Route(name: 'create', methods: ['POST'])]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'User created',
        content: new OA\JsonContent(ref: new Model(type: User::class, groups: ['user:read']))
    )]
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
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'List of users',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'users',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: User::class, groups: ['user:read']))
                ),
                new OA\Property(
                    property: 'pagination',
                    ref: new Model(type: PaginatorView::class)
                ),
            ],
        )
    )]
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

    #[Route('/me', name: 'me', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Current user',
        content: new OA\JsonContent(ref: new Model(type: User::class, groups: ['user:read']))
    )]
    public function me(): JsonResponse
    {
        return $this->json(data: $this->getUser(), context: ['groups' => ['user:read']]);
    }
}

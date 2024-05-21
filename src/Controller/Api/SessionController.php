<?php

namespace App\Controller\Api;

use App\Entity\User;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[OA\Tag(name: 'session')]
final class SessionController extends AbstractController
{
    #[Route('/api/session', name: 'api_session_create', methods: ['POST'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Session created',
        content: new OA\JsonContent(ref: new Model(type: User::class, groups: ['user:execute']))
    )]
    public function create(#[CurrentUser] ?User $user = null): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
        ]);
    }

    #[Route('/api/oauth/{service}/session', name: 'api_session_oauth_create', methods: ['GET', 'POST'])]
    #[OA\Response(
        description: 'Not implemented',
        response: Response::HTTP_NOT_IMPLEMENTED
    )]
    public function connect(Request $request, ClientRegistry $clientRegistry): JsonResponse
    {
        return $this->json([
            'message' => 'Not implemented',
        ], Response::HTTP_NOT_IMPLEMENTED);
    }
}

<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class SessionController extends AbstractController
{
    #[Route('/api/session', name: 'api_session_create', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user = null): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'username' => $user->getUsername(),
        ]);
    }

    #[Route('/api/oauth/{service}/session', name: 'api_session_oauth_create', methods: ['GET', 'POST'])]
    public function connect(Request $request, ClientRegistry $clientRegistry): JsonResponse
    {
        return $this->json([
            'message' => 'Connect with ' . $request->attributes->get('service'),
        ]);
    }
}

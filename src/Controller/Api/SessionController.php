<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class SessionController extends AbstractController
{
    #[Route('/api/session', name: 'api_session_create', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user = null): Response
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
}

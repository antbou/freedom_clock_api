<?php

namespace App\Controller\Api;

use App\Model\OAuth2\ConnectOAuth2DTO;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OAuth2LinkController extends AbstractController
{


    private const SCOPES = ['google' => []];

    public function __construct(
        private ClientRegistry $clientRegistry
    ) {
    }

    #[Route('/api/oauth', name: 'api_auth_oauth_connect',  methods: ['POST'])]
    public function create(#[MapRequestPayload] ConnectOAuth2DTO $Oauth2): JsonResponse
    {

        $client = $this->clientRegistry->getClient($Oauth2->service);

        /**
         * @var OAuth2ClientInterface $client
         */
        $callback = sprintf('%s/authorize/%s/callback', $this->getParameter('app.frontend_url'), $Oauth2->service);

        $link = $client->redirect(
            self::SCOPES[$Oauth2->service],
            ['redirect_uri' => $callback]
        )->getTargetUrl();

        return $this->json(['link' => $link])->setEncodingOptions(JSON_UNESCAPED_SLASHES);
    }

    #[Route('/authorize/callback', name: 'api_auth_oauth_callback',  methods: ['GET'])]
    public function callback(): never
    {
    }
}

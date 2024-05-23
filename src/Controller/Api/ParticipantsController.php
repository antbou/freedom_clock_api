<?php

namespace App\Controller\Api;

use App\Entity\Quiz;
use App\Entity\Participant;
use App\View\PaginatorView;
use OpenApi\Attributes as OA;
use App\Factory\ParticipantFactory;
use App\Repository\ParticipantRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Model\Participant\ParticipantsDTO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('api/quizzes/{id}/participants', requirements: ['id' => Requirement::UUID], name: 'api_participants_', format: 'json')]
#[OA\Tag(name: 'participants')]
final class ParticipantsController extends AbstractController
{
    public function __construct(
        private readonly ParticipantRepository $participantRepository
    ) {
    }

    #[Route(name: 'create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'User created',
        content: new OA\JsonContent(ref: new Model(type: Participant::class, groups: ['participant:read', 'quiz:read', 'user:read']))
    )]
    public function create(Quiz $quiz): JsonResponse
    {
        $participant = ParticipantFactory::findOrCreate([
            'quiz' => $quiz,
            'user' => $this->getUser()
        ]);

        return $this->json(
            data: ['participant' => $participant->object()],
            status: JsonResponse::HTTP_CREATED,
            context: ['groups' => [
                'participant:read',
                'quiz:read',
                'user:read'
            ]]
        );
    }

    #[Route(name: 'list', methods: ['GET'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'List of participants',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'participants',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Participant::class, groups: ['participant:read', 'user:read']))
                ),
                new OA\Property(
                    property: 'pagination',
                    type: 'object',
                    ref: new Model(type: PaginatorView::class)
                )
            ]
        )
    )]
    public function list(
        Quiz $quiz,
        #[MapQueryString] ParticipantsDTO $participantsDto = null
    ): JsonResponse {

        $participants = $this->participantRepository->findByQuiz(
            quiz: $quiz,
            page: $participantsDto->pagination?->page,
            limit: $participantsDto->pagination?->limit
        );

        return $this->json(
            data: $participants,
            status: JsonResponse::HTTP_OK,
            context: ['groups' => [
                'participant:read',
                'user:read'
            ]]
        );
    }
}

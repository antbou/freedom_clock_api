<?php

namespace App\Controller\Api;

use App\Entity\Quiz;
use App\Factory\ParticipantFactory;
use App\Repository\ParticipantRepository;
use App\Model\Participant\ParticipantsDTO;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('api/quizzes/{id}/participants', requirements: ['id' => Requirement::UUID], name: 'api_participants_', format: 'json')]
final class ParticipantsController extends AbstractController
{

    public function __construct(
        private readonly ParticipantRepository $participantRepository
    ) {
    }

    #[Route(name: 'create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
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
    public function list(
        Quiz $quiz,
        #[MapRequestPayload(acceptFormat: 'json')] ParticipantsDTO $participantsDto,
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

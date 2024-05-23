<?php

namespace App\Controller\Api;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Participant;
use OpenApi\Attributes as OA;
use App\Factory\AnswerFactory;
use App\Model\Answer\AnswerDTO;
use App\Factory\ParticipantFactory;
use App\Repository\AnswerRepository;
use App\Repository\OptionRepository;
use App\Model\Answer\CreateAnswerDTO;
use App\Repository\ParticipantRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('api/questions/{id}/answers', requirements: ['id' => Requirement::UUID],  name: 'api_answers_', format: 'json')]
#[OA\Tag(name: 'questions')]
final class AnswersController extends AbstractController
{
    public function __construct(
        private readonly OptionRepository $optionRepository,
        private readonly AnswerRepository $answerRepository,
        private readonly ParticipantRepository $participantRepository
    ) {
    }

    #[IsGranted('ROLE_USER')]
    #[Route(name: 'create', methods: ['POST'])]
    #[OA\Response(
        response: JsonResponse::HTTP_CREATED,
        description: 'Answer created',
        content: new OA\JsonContent(ref: new Model(type: Answer::class, groups: ['answer:read']))
    )]
    public function create(
        Question $question,
        #[MapRequestPayload(acceptFormat: 'json')] CreateAnswerDTO $createAnswerDTO
    ): JsonResponse {
        $option = $this->optionRepository->find($createAnswerDTO->optionId);
        $participant = ParticipantFactory::findOrCreate([
            'quiz' => $question->getQuiz(),
            'user' => $this->getUser()
        ]);

        $answer = AnswerFactory::findOrCreate([
            'participant' => $participant,
            'selectedOption' => $option,
            'question' => $question
        ]);

        return $this->json(
            data: ['answer' => $answer->object()],
            status: JsonResponse::HTTP_CREATED,
            context: ['groups' => ['answer:read']]
        );
    }

    #[Route(name: 'show', methods: ['GET'])]
    #[OA\Response(
        response: JsonResponse::HTTP_OK,
        description: 'List of answers',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'answersByParticipants',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(
                                property: 'participant',
                                ref: new Model(type: Participant::class, groups: ['participant:read', 'user:read'])
                            ),
                            new OA\Property(
                                property: 'answers',
                                type: 'array',
                                items: new OA\Items(ref: new Model(type: Answer::class, groups: ['answer:read', 'option:read']))
                            ),
                        ]
                    )
                ),
            ],
        )
    )]
    public function show(
        Question $question,
        #[MapQueryString] AnswerDTO $answerDto = null
    ): JsonResponse {
        $participants = $this->participantRepository->findByIdsAndQuiz(
            ids: $answerDto->participantIds,
            quiz: $question->getQuiz()
        );

        $answers = array_map(fn (Participant $participant) => [
            'participant' => $participant,
            'answers' => $participant->getAnswers(),
        ], $participants);

        return $this->json(
            data: ['answersByParticipants' => $answers],
            context: ['groups' => ['answer:read', 'option:read', 'participant:read', 'user:read']]
        );
    }
}

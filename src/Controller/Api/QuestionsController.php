<?php

namespace App\Controller\Api;

use App\Entity\Quiz;
use App\Entity\Question;
use App\Factory\ImageFactory;
use OpenApi\Attributes as OA;
use App\Factory\QuestionFactory;
use App\Entity\Enum\QuestionType;
use App\Security\Voter\QuestionVoter;
use App\Model\Question\CreateQuestionDTO;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('api/quizzes/{id}/questions', requirements: ['id' => Requirement::UUID], name: 'api_questions_', format: 'json')]
#[OA\Tag(name: 'questions')]
final class QuestionsController extends AbstractController
{
    #[Route(name: 'create', methods: ['POST'])]
    #[IsGranted(attribute: QuestionVoter::CREATE, subject: 'quiz', message: 'You must be the quiz author to create a question related to it', statusCode: Response::HTTP_UNAUTHORIZED)]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Question created',
        content: new OA\JsonContent(
            ref: new Model(type: Question::class, groups: ['question:read', 'image:read'])
        )
    )]
    public function create(
        Quiz $quiz,
        #[MapRequestPayload(acceptFormat: 'json')] CreateQuestionDTO $questionDto
    ): JsonResponse {
        $question = QuestionFactory::createOne([
            'text' => $questionDto->text,
            'type' => QuestionType::from($questionDto->type),
            'quiz' => $quiz,
            'image' => ImageFactory::createOne(
                ['createdBy' => $this->getUser()]
            )
        ]);

        return $this->json(
            data: $question->object(),
            status: JsonResponse::HTTP_CREATED,
            context: ['groups' => ['question:read', 'image:read']],
        );
    }

    #[Route(name: 'show', methods: ['GET'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'List of questions',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'questions',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Question::class, groups: ['question:read', 'image:read']))
                )
            ]
        )
    )]
    public function show(Quiz $quiz): JsonResponse
    {
        $questions = $quiz->getQuestions();

        return $this->json(
            data: ['questions' => $questions->toArray()],
            status: Response::HTTP_OK,
            context: ['groups' =>  ['question:read', 'image:read']]
        );
    }
}

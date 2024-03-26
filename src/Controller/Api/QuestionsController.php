<?php

namespace App\Controller\Api;

use App\Entity\Quiz;
use App\Entity\QuestionType;
use App\Factory\ImageFactory;
use App\Factory\QuestionFactory;
use App\Model\Question\CreateQuestionDTO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('api/quizzes/{id}/questions', requirements: ['id' => Requirement::UUID], name: 'api_questions_', format: 'json')]
final class QuestionsController extends AbstractController
{
    #[Route(name: 'create', methods: ['POST'])]
    #[IsGranted(attribute: 'create', subject: 'quiz', message: 'You must be the quiz author to create a question related to it',  statusCode: Response::HTTP_UNAUTHORIZED)]
    public function create(
        Quiz $quiz,
        #[MapRequestPayload(acceptFormat: 'json')] CreateQuestionDTO $questionDto
    ): JsonResponse {
        $question = QuestionFactory::createOne([
            'text' => $questionDto->text,
            'type' => QuestionType::MULTIPLE_CHOICE,
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

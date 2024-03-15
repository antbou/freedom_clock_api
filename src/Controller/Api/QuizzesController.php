<?php

namespace App\Controller\Api;

use App\Factory\QuizFactory;
use App\Model\Quiz\CreateQuizDTO;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('api/quizzes', name: 'api_quizzes_', format: 'json')]
final class QuizzesController extends AbstractController
{
    #[Route(name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] CreateQuizDTO $quizDto
    ): JsonResponse {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $quiz = QuizFactory::createOne([
            'title' => $quizDto->title,
            'introduction' => $quizDto->introduction,
            'createdBy' => $this->getUser(),
        ]);

        return new JsonResponse(['id' => $quiz->getId()], JsonResponse::HTTP_CREATED);
    }
}

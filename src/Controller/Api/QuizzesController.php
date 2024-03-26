<?php

namespace App\Controller\Api;

use App\Factory\QuizFactory;
use App\Factory\ImageFactory;
use App\Model\Quiz\QuizzesDTO;
use App\Model\Quiz\CreateQuizDTO;
use App\Repository\QuizRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('api/quizzes', name: 'api_quizzes_', format: 'json')]
final class QuizzesController extends AbstractController
{
    public function __construct(
        private QuizRepository $quizRepository
    ) {
    }

    #[Route(name: 'create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] CreateQuizDTO $quizDto
    ): JsonResponse {
        $quiz = QuizFactory::createOne([
            'title' => $quizDto->title,
            'introduction' => $quizDto->introduction,
            'createdBy' => $this->getUser(),
            'image' => ImageFactory::createOne(
                ['createdBy' => $this->getUser()]
            )
        ]);

        return $this->json(
            data: $quiz->object(),
            status: JsonResponse::HTTP_CREATED,
            context: ['groups' => ['quiz:read', 'image:read']],
        );
    }

    #[Route(name: 'list', methods: ['GET'])]
    public function list(
        #[MapRequestPayload(acceptFormat: 'json')] QuizzesDTO $quizzesDto
    ): JsonResponse {
        $quizzes = $this->quizRepository->findByIds(
            page: $quizzesDto->pagination?->page,
            limit: $quizzesDto->pagination?->limit,
            ids: $quizzesDto->ids
        );

        return $this->json(data: $quizzes, status: Response::HTTP_OK, context: ['groups' => [
            'quiz:read',
            'image:read',
            'user:read'
        ]]);
    }
}

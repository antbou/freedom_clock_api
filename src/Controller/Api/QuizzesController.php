<?php

namespace App\Controller\Api;

use App\Entity\Quiz;
use App\Factory\QuizFactory;
use App\Factory\ImageFactory;
use App\Model\Quiz\QuizzesDTO;
use App\Entity\Enum\QuizStatus;
use App\Model\Quiz\CreateQuizDTO;
use App\Model\Quiz\StatusQuizDTO;
use App\Security\Voter\QuizVoter;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('api/quizzes', name: 'api_quizzes_', format: 'json')]
final class QuizzesController extends AbstractController
{
    public function __construct(
        private QuizRepository $quizRepository,
        private EntityManagerInterface $entityManager
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

    #[Route('/{id}/status', name: 'status', methods: ['PATCH'], requirements: ['id' => Requirement::UUID])]
    #[IsGranted(attribute: QuizVoter::UPDATE, subject: 'quiz', message: 'You must be the quiz author to update its status', statusCode: Response::HTTP_UNAUTHORIZED)]
    public function status(Quiz $quiz, #[MapRequestPayload(acceptFormat: 'json')] StatusQuizDTO $statusQuizDto): Response
    {
        $quiz->setStatus(QuizStatus::from($statusQuizDto->status));
        $this->entityManager->flush();

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}

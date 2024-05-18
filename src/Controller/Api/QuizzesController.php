<?php

namespace App\Controller\Api;

use App\Entity\Quiz;
use App\Entity\User;
use App\View\PaginatorView;
use App\Factory\ImageFactory;
use OpenApi\Attributes as OA;
use App\Model\Quiz\QuizzesDTO;
use App\Entity\Enum\QuizStatus;
use App\Model\Quiz\StatusQuizDTO;
use App\Security\Voter\QuizVoter;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('api/quizzes', name: 'api_quizzes_', format: 'json')]
#[OA\Tag(name: 'quizzes')]
final class QuizzesController extends AbstractController
{
    public function __construct(
        private QuizRepository $quizRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route(name: 'create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Quiz created',
        content: new OA\JsonContent(ref: new Model(type: Quiz::class, groups: ['quiz:read', 'image:read']))
    )]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json', serializationContext: [
            'groups' => ['quiz:write']
        ])] Quiz $quiz
    ): JsonResponse {
        $image = ImageFactory::createOne(['createdBy' => $this->getUser()])->object();
        $quiz->setImage($image);
        $quiz->setCreatedBy($this->getUser());
        $this->entityManager->persist($quiz);
        $this->entityManager->flush();

        return $this->json(
            data: $quiz,
            status: JsonResponse::HTTP_CREATED,
            context: ['groups' => ['quiz:read', 'image:read']],
        );
    }

    #[Route(name: 'list', methods: ['GET'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'List of quizzes',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'quizzes',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Quiz::class, groups: ['quiz:read', 'image:read', 'user:read']))
                ),
                new OA\Property(
                    property: 'pagination',
                    ref: new Model(type: PaginatorView::class)
                )
            ]
        )
    )]
    public function list(
        #[MapQueryString] QuizzesDTO $quizzesDto = null
    ): JsonResponse {
        $quizzes = $this->quizRepository->findByIds(
            page: $quizzesDto?->pagination?->page,
            limit: $quizzesDto?->pagination?->limit,
            ids: $quizzesDto?->ids
        );

        return $this->json(data: $quizzes, status: Response::HTTP_OK, context: ['groups' => [
            'quiz:read',
            'image:read',
            'user:read'
        ]]);
    }

    #[Route('/{id}/status', name: 'status', methods: ['PATCH'], requirements: ['id' => Requirement::UUID])]
    #[IsGranted(attribute: QuizVoter::UPDATE, subject: 'quiz', message: 'You must be the quiz author to update its status', statusCode: Response::HTTP_UNAUTHORIZED)]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Quiz status updated')]
    public function status(Quiz $quiz, #[MapRequestPayload(acceptFormat: 'json')] StatusQuizDTO $statusQuizDto): Response
    {
        $quiz->setStatus(QuizStatus::from($statusQuizDto->status));
        $this->entityManager->flush();

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}

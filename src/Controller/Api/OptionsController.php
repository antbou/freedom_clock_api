<?php

namespace App\Controller\Api;

use App\Entity\Option;
use App\Entity\Question;
use App\Factory\ImageFactory;
use App\Security\Voter\OptionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('api/questions/{id}/options', requirements: ['id' => Requirement::UUID], name: 'api_options_', format: 'json')]
final class OptionsController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route(name: 'create', methods: ['POST'])]
    #[IsGranted(attribute: OptionVoter::CREATE, subject: 'question', message: 'You must be the question author to create an option related to it', statusCode: Response::HTTP_UNAUTHORIZED)]
    public function create(
        Question $question,
        #[MapRequestPayload(
            acceptFormat: 'json',
            serializationContext: [
                'groups' => ['option:write']
            ]
        )] Option $option
    ): JsonResponse {

        $option->setQuestion($question);
        $option->setImage(ImageFactory::createOne(
            ['createdBy' => $this->getUser()]
        )->object());

        $this->entityManager->persist($option);
        $this->entityManager->flush();

        return $this->json(
            data: $option,
            status: Response::HTTP_CREATED,
            context: ['groups' => ['option:read', 'image:read']]
        );
    }

    #[Route(name: 'show', methods: ['GET'])]
    public function show(Question $question): JsonResponse
    {
        $options = $question->getOptions();

        return $this->json(
            data: ['options' => $options->toArray()],
            status: Response::HTTP_OK,
            context: ['groups' =>  ['option:read', 'image:read']]
        );
    }
}

<?php

namespace App\Controller\Api;

use App\Entity\Question;
use App\Factory\ImageFactory;
use App\Factory\OptionFactory;
use App\Model\Option\CreateOptionDTO;
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
    #[Route(name: 'create', methods: ['POST'])]
    #[IsGranted('create', subject: 'question', message: 'You must be the question author to create an option related to it', statusCode: Response::HTTP_UNAUTHORIZED)]
    public function create(
        Question $question,
        #[MapRequestPayload(acceptFormat: 'json')] CreateOptionDTO $optionDto
    ): JsonResponse {

        $option = OptionFactory::createOne([
            'text' => $optionDto->text,
            'isCorrect' => $optionDto->isCorrect,
            'question' => $question,
            'image' => ImageFactory::createOne(
                ['createdBy' => $this->getUser()]
            )
        ]);

        return $this->json(
            data: $option->object(),
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

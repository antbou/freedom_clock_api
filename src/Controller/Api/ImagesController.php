<?php

namespace App\Controller\Api;

use App\Entity\Image;
use OpenApi\Attributes as OA;
use App\Service\ImageValidator;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('api/images/{id}', name: 'api_images_', format: 'json', requirements: ['id' => Requirement::UUID])]
#[OA\Tag(name: 'images')]
final class ImagesController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ImageValidator $imageValidator
    ) {
    }

    #[Route(name: 'show', methods: ['GET'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Image details',
        content: new OA\JsonContent(ref: new Model(type: Image::class, groups: ['image:read']))
    )]
    public function show(Image $image): JsonResponse
    {
        return $this->json(data: $image, context: [
            'groups' => ['image:read']
        ]);
    }

    #[Route(name: 'create', methods: ['POST'])]
    #[IsGranted(attribute: 'update', subject: 'image', message: 'You must be the ressource author to create an image related to it',  statusCode: Response::HTTP_UNAUTHORIZED)]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Image created',
        content: new OA\JsonContent(ref: new Model(type: Image::class, groups: ['image:read']))
    )]
    public function upload(Image $image, Request $request): JsonResponse
    {
        $uploadedFile = $request->files->get('file') ?? null;
        $errors = $this->imageValidator->validate($uploadedFile);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $image->setFile($uploadedFile);
        $this->em->persist($image);
        $this->em->flush();

        return $this->json(data: $image, status: Response::HTTP_CREATED, context: ['groups' => ['image:read']]);
    }
}

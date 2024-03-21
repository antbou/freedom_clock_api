<?php

namespace App\Controller\Api;

use App\Entity\Image;
use App\Service\ImageValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('api/images', name: 'api_images_')]
final class ImagesController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private EntityManagerInterface $em,
        private ImageValidator $imageValidator
    ) {
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Image $image, UploaderHelper $uploaderHelper): JsonResponse
    {
        $url = $uploaderHelper->asset($image);

        return $this->json([
            'id' => $image->getId(),
            'url' => $url,
        ])->setEncodingOptions(JSON_UNESCAPED_SLASHES);
    }

    #[Route('/{id}', name: 'create', methods: ['POST'], format: 'form')]
    #[IsGranted(attribute: 'update', subject: 'image', message: 'You must be the quiz author to create an image related to it',  statusCode: Response::HTTP_FORBIDDEN)]
    public function create(Image $image, Request $request): JsonResponse
    {
        $uploadedFile = $request->files->get('file') ?? null;
        $errors = $this->imageValidator->validate($uploadedFile);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $image->setFile($uploadedFile);
        $this->em->persist($image);
        $this->em->flush();

        return $this->json([
            'id' => $image->getId(),
            'filename' => $image->getFilename(),
        ]);
    }
}

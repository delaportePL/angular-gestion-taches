<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

use App\Service\MongoDBClient;


class SwaggerController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('swagger');
    }

    #[Route('/api/doc', name: 'swagger', methods: ["GET"])]
    public function showSwagger(): Response
    {
        return $this->forward('nelmio_api_doc.controller.swagger_ui');
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SwaggerController extends AbstractController
{
    #[Route('/', name: 'swagger')]
    public function index(): Response
    {
        return $this->forward('nelmio_api_doc.controller.swagger_ui');
    }
}
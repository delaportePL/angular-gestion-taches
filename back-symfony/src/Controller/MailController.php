<?php

namespace App\Controller;

use App\Service\UserService;
use App\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Attributes as OA;
use OA\JsonContent;
use OA\Property;

class MailController extends AbstractController
{
    #[Route('/api/users/list', name: 'listUsers', methods: ["GET"])]
    #[OA\Tag(name: 'Users')]
    #[OA\Response(response: 200, description: 'Renvoie la liste de tous les users')]
    /**
     *Listage de tous les users
     * @param Request $request
     * @return JsonResponse
     */
    public function listUsers(UserService $userService): JsonResponse
    {
        return new JsonResponse($userService->listUsers());
    }

    #[Route('/api/users/sendMail', name: 'sendMail', methods: ["POST"])]
    #[OA\Tag(name: 'Users')]
    #[OA\Response(response: 200, description: 'Retourne un message de confirmation ou un message d\'erreur')]
    /**
     *Envoie d'un mail à un user
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMailNewResponsability(MailService $mailService, Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        return new JsonResponse($mailService->sendMailNewResponsability($requestData["email"]));
    }


}
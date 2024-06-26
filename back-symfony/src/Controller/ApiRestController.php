<?php

namespace App\Controller;

use App\Service\TaskService;
use App\Service\MailService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Attributes as OA;
use OA\JsonContent;
use OA\Property;


class ApiRestController extends AbstractController
{
    #[Route('/api/tasks/list', name: 'listTasks', methods: ["GET"])]
    #[OA\Tag(name: 'Tasks')]
    #[OA\Response(
        response: 200,
        description: 'Renvoie la liste de toutes les tâches',
    )]
    /**
     * Listage de toutes les tâches
     * @param Request $request
     * @return JsonResponse
     */
    public function listTasks(TaskService $TaskService): JsonResponse
    {
        return new JsonResponse($TaskService->listTasks());
    }

    #[Route('/api/tasks/listAssignedByUserId/{user_id}', name: 'listAssignedTasksByUserId', methods: ["GET"])]
    #[OA\Tag(name: 'Tasks')]
    #[OA\Response(
        response: 200,
        description: 'Renvoie la liste de toutes les tâches assignées à un utilisateur'
    )]
    /**
     * Listage de toutes les tâches assignées à un utilisateur
     * @param Request $request
     * @return JsonResponse
     */
    public function listAssignedTasksByUserId(string $user_id, Request $request, TaskService $TaskService): JsonResponse {
        return new JsonResponse($TaskService->listTasksById($user_id));
    }

    
    #[Route('/api/tasks/add', name: 'addTask', methods: ["POST"])]
    #[OA\Tag(name: 'Tasks')]
    #[OA\Response(
        response: 200,
        description: 'Renvoie un message de confirmation ou d\'erreur'
    )]
    #[OA\RequestBody(  
        description: 'Entrer les clés et les valeurs des champs à insérer. (champs obligatoires => creator)',    
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'project_id', type:'string'),
                new OA\Property(property: 'title', type:'string'),
                new OA\Property(property: 'description', type:'string'),
                new OA\Property(property: 'state', type:'string'),
                // new OA\Property(property: 'responsability', type:'string'),
                new OA\Property(property: 'assigned_user_id', type:'string'),
                // new OA\Property(property: 'criticaly', type:'int'),
                new OA\Property(property: 'points', type:'int'),
                new OA\Property(property: 'creator', type:'string'),
            ],
        )
    )]
    /**
     * Ajout d'une tâche, en spécifiant son type
     * @param Request $request
     * @return JsonResponse
     */

    public function addTask(Request $request, TaskService $taskService, MailService $mailService, UserService $userService): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $result = $taskService->addTask($requestData);

        if ($result["message"] == "Tâche créée avec succès"){
                if ($result["users"] != null && isset($result["users"])){
                    $responseEmail = $userService->getUserMail($result["users"]);
                    if ($responseEmail){
                        $mailService->sendMailNewResponsability($responseEmail, $userService->getFirstName($result["users"]), $userService->getLastName($result["users"]));
                    }
                }
                unset($result["users"]);
            }
        return new JsonResponse($result);
    }


    #[Route('/api/tasks/update/{taskId}', name: 'updateTask', methods: ["PUT"])]
    #[OA\Tag(name: 'Tasks')]
    #[OA\Response(response: 200, description: 'Renvoie un message de confirmation ou d\'erreur')]
    #[OA\RequestBody(  
        description: 'Entrer les clés et les valeurs des champs à modifier',    
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'title', type:'string'),
                new OA\Property(property: 'description', type:'string'),
                new OA\Property(property: 'state', type:'string'),
                new OA\Property(property: 'responsability', type:'string'),
                new OA\Property(property: 'criticaly', type:'int'),
                new OA\Property(property: 'creator', type:'string'),
            ]
        )
    )]
    /**
     * Mise à jour d'une tâche, à l'aide de son ID
     * @param Request $request
     * @return JsonResponse
     */
    public function updateTask(string $taskId, Request $request, TaskService $taskService, UserService $userService, MailService $mailService): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $result = $taskService->updateTask($taskId, $requestData);

        if ($result["message"] == "Tâche mise à jour avec succès" && isset($result["users"])){
                if ($result["users"] != null && $result["users"]){
                    $responseEmail = $userService->getUserMail($result["users"]);
                    if ($responseEmail){
                        $mailService->sendMailNewResponsability($responseEmail, $userService->getFirstName($result["users"]), $userService->getLastName($result["users"]));
                    }
                }
            unset($result["users"]);
        }
        return new JsonResponse($result);
    }

    #[Route('/api/tasks/delete/{taskId}', name: 'deleteTask', methods: ["DELETE"])]
    #[OA\Tag(name: 'Tasks')]
    #[OA\Response(response: 200,description: 'Renvoie un message de confirmation ou d\'erreur')]
    /**
     * Suppression d'une tâche, à l'aide de son ID
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteTask(string $taskId, TaskService $TaskService): JsonResponse
    {
        return new JsonResponse($TaskService->deleteTask($taskId));
    }
}
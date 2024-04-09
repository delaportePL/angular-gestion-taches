<?php

namespace App\Controller;

use App\Service\MongoDBClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Attributes as OA;
use OA\JsonContent;
use OA\Property;

use DateTime;
use DateTimeZone;


class ApiRestController extends AbstractController
{
    
    #[Route('/api/tasks/list', name: 'listTasks', methods: ["GET"])]
    #[OA\Tag(name: 'Tasks')]
    #[OA\Response(
        response: 200,
        description: 'Renvoie la liste de toutes les tâches',
    )]
    #[OA\RequestBody(description: 'Pas de body attendu')]
    /**
     *Listage de toutes les tâches
     * @param Request $request
     * @return JsonResponse
     */
    public function listTasks(MongoDBClient $mongoDBClient): JsonResponse
    {
        return new JsonResponse($mongoDBClient->listTasks());
    }


    #[Route('/api/tasks/listById{taskId}', name: 'listTasksById', methods: ["GET"])]
    #[OA\Tag(name: 'Tasks')]
    #[OA\Response(
        response: 200,
        description: 'Renvoie la liste de toutes les tâches ayant un certain ID'
    )]
    #[OA\RequestBody(description: 'Pas de body attendu')]
    /**
     *Listage de toutes les tâches ayant un certain ID
     * @param Request $request
     * @return JsonResponse
     */
    public function listTasksById(string $taskId, Request $request, MongoDBClient $mongoDBClient): JsonResponse
    {
        $collection = $mongoDBClient->getClient()->selectDatabase('task-management')->selectCollection('tasks');
        $tasks = $collection->find(["idTask" => $taskId]);

        $tasksList = [];
        foreach ($tasks as $task) {
            $tasksList[] = $task;
        }
        return new JsonResponse($tasksList);
    }


    #[Route('/api/tasks/add/{type}', name: 'addTask', methods: ["POST"])]
    #[OA\Tag(name: 'Tasks')]
    #[OA\Response(
        response: 200,
        description: 'Renvoie un message de confirmation ou d\'erreur'
    )]
    #[OA\RequestBody(  
        description: 'Entrer les clés et les valeurs des champs à inserer',    
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
     *Ajout d'une tâche, en spécifiant son type
     * @param Request $request
     * @return JsonResponse
     */
    public function addTask(string $type, Request $request, MongoDBClient $mongoDBClient): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        return new JsonResponse($mongoDBClient->addTask($type, $requestData));
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
     *Mise à jour d'une tâche, à l'aide de son ID
     * @param Request $request
     * @return JsonResponse
     */
    public function updateTask(string $taskId, Request $request, MongoDBClient $mongoDBClient): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $collection = $mongoDBClient->getClient()->selectDatabase('task-management')->selectCollection('tasks');
        $existingTask = $collection->findOne(['idTask' => $taskId]);

        if (!$existingTask) {
            return new JsonResponse(['message' => 'Tâche non trouvée'], Response::HTTP_NOT_FOUND);
        }

        unset($requestData['idTask']);
        unset($requestData['dateCreation']);

        $requestData['dateUpdate'] = (new DateTime('now', new DateTimeZone('Europe/Paris')))->format('Y-m-d\TH:i:s');
        $updateResult = $collection->updateOne(['idTask' => $taskId], ['$set' => $requestData]);

        if ($updateResult->getModifiedCount() === 1) {
            return new JsonResponse(['message' => 'Tâche mise à jour avec succès'], Response::HTTP_OK);
        } else {
            return new JsonResponse(['message' => 'Échec de la mise à jour de la tâche'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/api/tasks/delete/{taskId}', name: 'deleteTask', methods: ["DELETE"])]
    #[OA\Tag(name: 'Tasks')]
    #[OA\Response(response: 200,description: 'Renvoie un message de confirmation ou d\'erreur')]
    #[OA\RequestBody(description: 'Pas de body attendu')]
    /**
     *Suppression d'une tâche, à l'aide de son ID
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteTask(string $taskId, MongoDBClient $mongoDBClient): JsonResponse
    {
        $collection = $mongoDBClient->getClient()->selectDatabase('task-management')->selectCollection('tasks');
        $deleteResult = $collection->deleteOne(['idTask' => $taskId]);

        if ($deleteResult->getDeletedCount() === 1) {
            return new JsonResponse(['message' => 'Tâche supprimée avec succès'], Response::HTTP_OK);
        } else {
            return new JsonResponse(['message' => 'Échec de la suppression de la tâche'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
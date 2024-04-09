<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;


use App\Service\MongoDBClient;

class ApiRestController extends AbstractController
{
    #[Route('/api/tasks', name: 'allTasks', methods: ["GET"])]
    public function allTasks(MongoDBClient $mongoDBClient): JsonResponse
    {
        $collection = $mongoDBClient->getClient()->selectDatabase('task-management')->selectCollection('tasks');
        $tasks = $collection->find();
        $tasksList = [];
        foreach ($tasks as $task) {
            $tasksList[] = $task;
        }
        return new JsonResponse($tasksList);
    }

    //?id=1,2,3
    #[Route('/api/tasks/{taskId}', name: 'tasksById', methods: ["GET"])]
    public function tasksById(Request $request, MongoDBClient $mongoDBClient): JsonResponse
    {
        $collection = $mongoDBClient->getClient()->selectDatabase('task-management')->selectCollection('tasks');
        
        $filter = ['idTask' => $request->attributes->get('taskId')];
        $tasks = $collection->find();

        $tasksList = [];
        foreach ($tasks as $task) {
            $tasksList[] = $task;
        }
        return new JsonResponse($tasksList);
    }


    #[Route('/api/tasks/add', name: 'tasksAdd', methods: ["POST"])]
    public function createTask(Request $request, MongoDBClient $mongoDBClient): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (empty($requestData['description']) || empty($requestData['status'])) {
            return new JsonResponse(['message' => 'Description et statut sont requis !'], Response::HTTP_BAD_REQUEST);
        }

        $newTask = [
            'description' => $requestData['description'],
            'status' => $requestData['status'],
            'responsibility' => $requestData['responsibility'] ?? [],
            'urgency' => $requestData['urgency'] ?? null,
            'creator' => $requestData['creator'] ?? null,
            'created_at' => new \DateTime(),
        ];
        
        $collection = $mongoDBClient->getClient()->selectDatabase('task-management')->selectCollection('tasks');
        $insertResult = $collection->insertOne($newTask);

        if ($insertResult->getInsertedCount() === 1) {
            return new JsonResponse(['message' => 'Tâche créée avec succès', 'id' => $insertResult->getInsertedId()], Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['message' => 'Échec de la création de la tâche'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
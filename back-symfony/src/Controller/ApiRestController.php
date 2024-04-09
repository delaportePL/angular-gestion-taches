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
    #[Route('/api/tasks', name: 'tasksGet', methods: ["GET"])]
    public function tasksGet(MongoDBClient $mongoDBClient): JsonResponse
    {
        $collection = $mongoDBClient->getClient()->selectDatabase('task-management')->selectCollection('tasks');
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

        $date = new \DateTime();
        $formattedDate = $date->format('Y-m-d\TH:i:s\Z');

        $newTask = [
            'idTask' => $requestData['idTask'],
            'title' => $requestData['title'],
            'description' => $requestData['description'],
            'state' => $requestData['state'],
            'responsibility' => $requestData['responsibility'] ?? [],
            'criticality' => $requestData['urgency'] ?? null,
            'creator' => $requestData['creator'] ?? null,
            'dateCreation' => $formattedDate
        ];

        $collection = $mongoDBClient->getClient()->selectDatabase('task-management')->selectCollection('tasks');
        $insertResult = $collection->insertOne($newTask);

        if ($insertResult->getInsertedCount() === 1) {
            return new JsonResponse(['message' => 'Tâche créée avec succès', 'id' => $insertResult->getInsertedId()], Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['message' => 'Échec de la création de la tâche'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/tasks/{id}', name: 'tasksDelete', methods: ["DELETE"])]
    public function deleteTask(string $id, MongoDBClient $mongoDBClient): JsonResponse
    {
        $collection = $mongoDBClient->getClient()->selectDatabase('task-management')->selectCollection('tasks');

        $deleteResult = $collection->deleteOne(['idTask' => $id]);

        if ($deleteResult->getDeletedCount() === 1) {
            return new JsonResponse(['message' => 'Tâche supprimée avec succès'], Response::HTTP_OK);
        } else {
            return new JsonResponse(['message' => 'Échec de la suppression de la tâche'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
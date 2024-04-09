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
use DateTime;
use DateTimeZone;



class ApiRestController extends AbstractController
{
    #[Route('/api/tasks/list', name: 'listTasks', methods: ["GET"])]
    public function listTasks(MongoDBClient $mongoDBClient): JsonResponse
    {
        $collection = $mongoDBClient->getClient()->selectDatabase('task-management')->selectCollection('tasks');
        $tasks = $collection->find();

        $tasksList = [];
        foreach ($tasks as $task) {
            $tasksList[] = $task;
        }
        return new JsonResponse($tasksList);
    }

    #[Route('/api/tasks/listById{taskId}', name: 'listTasksById', methods: ["GET"])]
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
    public function addTask(string $type, Request $request, MongoDBClient $mongoDBClient): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $lastTask = $mongoDBClient->getClient()->selectDatabase('task-management')->selectCollection('tasks')->findOne(['idTask' => new \MongoDB\BSON\Regex("^$type\-")], ['sort' => ['idTask' => -1]]);

        if ($lastTask) {
            $lastIdNumber = (int) substr($lastTask['idTask'], strlen($type) + 1);
            $newIdNumber = $lastIdNumber + 1;
            $newIdTask = $type . '-' . sprintf('%04d', $newIdNumber);
        } else {
            $newIdTask = $type . '-00001';
        }

        $newTask = [
            'idTask' => $newIdTask,
            'title' => $requestData['title'] ?? null,
            'description' => $requestData['description'] ?? null,
            'state' => $requestData['state'] ?? null,
            'responsibility' => $requestData['responsibility'] ?? [],
            'criticality' => $requestData['urgency'] ?? null,
            'creator' => $requestData['creator'] ?? null,
            'dateCreation' => (new DateTime('now', new DateTimeZone('Europe/Paris')))->format('Y-m-d\TH:i:s')
        ];

        $collection = $mongoDBClient->getClient()->selectDatabase('task-management')->selectCollection('tasks');
        $insertResult = $collection->insertOne($newTask);

        if ($insertResult->getInsertedCount() === 1) {
            return new JsonResponse(['message' => 'Tâche créée avec succès', 'id' => $insertResult->getInsertedId()], Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['message' => 'Échec de la création de la tâche'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/tasks/delete/{taskId}', name: 'deleteTask', methods: ["DELETE"])]
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

    #[Route('/api/tasks/update/{taskId}', name: 'updateTask', methods: ["PUT"])]
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
}
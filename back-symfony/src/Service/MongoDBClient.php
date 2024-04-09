<?php

namespace App\Service;

use MongoDB\Client;
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

class MongoDBClient
{
    private $client;
    private $collection;

    public function __construct(string $dsn)
    {
        $this->client = new Client($dsn);
        $this->collection = $this->client->selectDatabase('task-management')->selectCollection('tasks');
    }

    public function getClient(){
        return $this->client;
    }

    public function listTasks(): array
    {
        $tasks = $this->collection->find();

        $tasksList = [];
        foreach ($tasks as $task) {
            $tasksList[] = $task;
        }
        return $tasksList;
    }

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


    public function addTask(string $type, $requestData): array
    {
        $lastTask = $this->client->selectDatabase('task-management')->selectCollection('tasks')->findOne(['idTask' => new \MongoDB\BSON\Regex("^$type\-")], ['sort' => ['idTask' => -1]]);

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

        $collection = $this->client->selectDatabase('task-management')->selectCollection('tasks');
        $insertResult = $collection->insertOne($newTask);

        if ($insertResult->getInsertedCount() === 1) {
            return ['message' => 'Tâche créée avec succès'];
        } else {
            return ['message' => 'Échec de la création de la tâche'];
        }
    }
}

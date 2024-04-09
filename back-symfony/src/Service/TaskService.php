<?php

namespace App\Service;

use MongoDB\Client;
use DateTime;
use DateTimeZone;

class TaskService
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

    public function listTasksById(string $taskId): array
    {
        $task = $this->collection->findOne(["idTask" => $taskId]);

        return $task ? [$task] : null;
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
            'responsability' => $requestData['responsability'] ?? [],
            'criticality' => $requestData['urgency'] ?? null,
            'creator' => $requestData['creator'] ?? null,
            'dateCreation' => (new DateTime('now', new DateTimeZone('Europe/Paris')))->format('Y-m-d\TH:i:s')
        ];

        $collection = $this->client->selectDatabase('task-management')->selectCollection('tasks');
        $insertResult = $collection->insertOne($newTask);

        if ($insertResult->getInsertedCount() === 1) {
            return ['message' => 'Tâche créée avec succès', "users" => array_merge([$requestData['creator'] ?? null], $requestData['responsability']?? [])];
        } else {
            return ['message' => 'Échec de la création de la tâche'];
        }
    }

    public function updateTask(string $taskId, $requestData): array
    {
        $existingTask = $this->collection->findOne(['idTask' => $taskId]);

        if (!$existingTask) {
            return ['message' => 'Tâche non trouvée'];
        }

        unset($requestData['idTask']);
        unset($requestData['dateCreation']);

        $requestData['dateUpdate'] = (new DateTime('now', new DateTimeZone('Europe/Paris')))->format('Y-m-d\TH:i:s');
        $updateResult = $this->collection->updateOne(['idTask' => $taskId], ['$set' => $requestData]);

        if ($updateResult->getModifiedCount() === 1) {
            return ['message' => 'Tâche mise à jour avec succès'];
        } else {
            return ['message' => 'Échec de la mise à jour de la tâche'];
        }
    }

    public function deleteTask(string $taskId): array
    {
        $deleteResult = $this->collection->deleteOne(['idTask' => $taskId]);

        if ($deleteResult->getDeletedCount() === 1) {
            return ['message' => 'Tâche supprimée avec succès'];
        } else {
            return ['message' => 'Échec de la suppression de la tâche'];
        }
    }
}

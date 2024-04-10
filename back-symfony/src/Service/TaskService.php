<?php

namespace App\Service;

use MongoDB\Client;
use MongoDB\Model\BSONArray;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;
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
        $task = $this->collection->findOne(["_id" => new ObjectId($taskId)]);

        return $task ? [$task] : null;
    }

    public function addTask($requestData): array
    {
        $projectId = $requestData['projectId'];

         $lastTask = $this->client->selectDatabase('task-management')->selectCollection('tasks')->findOne(['idTask' => new \MongoDB\BSON\Regex("^" . $projectId . "\-")], ['sort' => ['idTask' => -1]]);

        if ($lastTask) {
            $lastIdNumber = (int) substr($lastTask['idTask'], strlen($projectId) + 1);
            $newIdNumber = $lastIdNumber + 1;
            $newIdTask = $projectId . '-' . sprintf('%05d', $newIdNumber);
        } else {
            $newIdTask = $projectId . '-00001';
        }

        $newTask = [
            'idTask' => $newIdTask,
            'projectId' => $projectId,
            'title' => $requestData['title'] ?? null,
            'description' => $requestData['description'] ?? null,
            'category' => $requestData['category'] ?? null,
            'state' => $requestData['state'] ?? null,
            'points' => $requestData['points'] ?? null,
            'assignedUserId' => $requestData['assignedUserId'] ?? null,
            'creationDate' => (new DateTime('now', new DateTimeZone('Europe/Paris')))->format('Y-m-d\TH:i:s'),
            'creatorUserId' => $requestData['creatorUserId']
        ];

        $collection = $this->client->selectDatabase('task-management')->selectCollection('tasks');
        $insertResult = $collection->insertOne($newTask);

        if ($insertResult->getInsertedCount() === 1) {
            return ['message' => 'Tâche créée avec succès', "users" =>  $requestData['assignedUserId']?? []];
        } else {
            return ['message' => 'Échec de la création de la tâche'];
        }
    }

    public function updateTask(string $taskId, $requestData): array
    {
        $existingTask = $this->collection->findOne(["_id" => new \MongoDB\BSON\ObjectId($taskId)]);

        if (!$existingTask) {
            return ['message' => 'Tâche non trouvée'];
        }

        unset($requestData['idTask']);
        unset($requestData['creationDate']);

        

        $requestData['modificationDate'] = (new DateTime('now', new DateTimeZone('Europe/Paris')))->format('Y-m-d\TH:i:s');
        $updateResult = $this->collection->updateOne(["_id" => new \MongoDB\BSON\ObjectId($taskId)], ['$set' => $requestData]);

        if ($updateResult->getModifiedCount() === 1) {
            if (isset($requestData['assignedUserId'])){
                return ['message' => 'Tâche mise à jour avec succès', "users" => $requestData['assignedUserId']];
            }
            return ['message' => 'Tâche mise à jour avec succès'];
        } else {
            return ['message' => 'Échec de la mise à jour de la tâche'];
        }
    }

    public function deleteTask(string $taskId): array
    {
        $deleteResult = $this->collection->deleteOne(["_id" => new \MongoDB\BSON\ObjectId($taskId)]);
        
        if ($deleteResult->getDeletedCount() === 1) {
            // Retrieve the updated list of tasks
            $tasks = $this->listTasks();
            return [
                'message' => 'Tâche supprimée avec succès',
                'tasks' => $tasks
        ];
        } else {
            return ['message' => 'Échec de la suppression de la tâche'];
        }
    }
    
}

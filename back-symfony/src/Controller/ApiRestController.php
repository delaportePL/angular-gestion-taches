<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

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
}
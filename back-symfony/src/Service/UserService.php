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


class UserService
{
    private $client;
    private $collection;

    public function __construct(string $dsn)
    {
        $this->client = new Client($dsn);
        $this->collection = $this->client->selectDatabase('task-management')->selectCollection('users');
    }

    public function listUsers(): array
    {
        $users = $this->collection->find();

        $usersList = [];
        foreach ($users as $user) {
            $usersList[] = $user;
        }
        return $usersList;
    }

    public function getUserMail(string $user): string
    {
        $existingUser = $this->collection->findOne(["_id" => new \MongoDB\BSON\ObjectId($user)]);
    
        if (!$existingUser) {
            return false;
        }
        return $existingUser["email"];
    }

    public function getFirstName(string $user): ?string
    {
        $existingUser = $this->collection->findOne(['user' => $user]);

        if (!$existingUser) {
            return null;
        }

        return $existingUser["firstName"] ?? null;
    }

    public function getLastName(string $user): ?string
    {
        $existingUser = $this->collection->findOne(['user' => $user]);

        if (!$existingUser) {
            return null;
        }

        return $existingUser["lastName"] ?? null;
    }
}

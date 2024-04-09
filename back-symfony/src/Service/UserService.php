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

}

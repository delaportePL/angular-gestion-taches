<?php

namespace App\Service;

use MongoDB\Client;

class MongoDBClient
{
    private $client;

    public function __construct(string $dsn)
    {
        $this->client = new Client($dsn);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}

<?php

namespace App\Repository;


use PDO;

class UserRepository
{
    /** @var PDO */
    private $pdo;

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        $db = [
            'host'      => getenv('DB_HOST'),
            'user'      => getenv('DB_USERNAME'),
            'pass'      => getenv('DB_PASSWORD'),
            'dbname'    => getenv('DB_DATABASE'),
        ];

        $this->pdo = new PDO(
            "mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
            $db['user'], $db['pass']
        );
    }

    public function find(...$conditions)
    {

    }

    public function insert(array $data)
    {

    }
}
<?php

namespace App\Repository;


use App\Entity\User;
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

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return User|bool
     */
    public function findById(int $id)
    {
        $sql = "SELECT * FROM `users` WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        return new User($result);
    }

    /**
     * @param $email
     * @param $password
     * @return User|bool
     */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM `users` WHERE `email` = ':email'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'email' => $email
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        return new User($result);
    }

    /**
     * @param $email
     * @param $password
     * @return User|bool
     */
    public function findByCredentials($email, $password)
    {
        $sql = "SELECT * FROM `users` WHERE `email` = ':email' AND `password` = ':password'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'password' => $password
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        return new User($result);
    }

    /**
     * @param array $data
     * @return User|bool
     */
    public function insert(array $data)
    {
        $sql = "INSERT INTO `users` (name, email, password) VALUES (':name', ':email', ':password')";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        $id = $this->pdo->lastInsertId();
        return $this->findById($id);
    }
}
<?php

require 'vendor/autoload.php';

use Slim\Http\Request;
use Slim\Http\Response;

$app = new Slim\App();

/**
 * Authenticate
 */
$app->put('/user', function (Request $request, Response $response) {
    $email = $request->getAttribute('email');
    $password = $request->getAttribute('password');

    $db = [
        'host'      => getenv('DB_HOST'),
        'user'      => getenv('DB_USERNAME'),
        'pass'      => getenv('DB_PASSWORD'),
        'dbname'    => getenv('DB_DATABASE'),
    ];

    $pdo = new PDO(
        "mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']
    );

    $result = $pdo->exec("SELECT id FROM `user` WHERE email=$email AND password=$password");

    if (!$result) {
        return $response->withStatus(404);
    }

    return $response->withJson($result);
});

/**
 * Register
 */
$app->post('/user', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $email = $request->getAttribute('email');
    $password = $request->getAttribute('password');

    $db = [
        'host'      => getenv('DB_HOST'),
        'user'      => getenv('DB_USERNAME'),
        'pass'      => getenv('DB_PASSWORD'),
        'dbname'    => getenv('DB_DATABASE'),
    ];

    $pdo = new PDO(
        "mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']
    );

    $result = $pdo->exec("INSERT INTO `user` (`name`, email, password) VALUES ($name, $email, $password");

    if (!$result) {
        return $response->withStatus(500);
    }

    return $response->withJson($result);
});

/**
 * Details
 */
$app->get('/user/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    return $response->withJson('Getting user with id '. $id);

    $db = [
        'host'      => getenv('DB_HOST'),
        'user'      => getenv('DB_USERNAME'),
        'pass'      => getenv('DB_PASSWORD'),
        'dbname'    => getenv('DB_DATABASE'),
    ];

    $pdo = new PDO(
        "mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']
    );

    $result = $pdo->exec("SELECT email, `name` FROM `user` WHERE id=$id");

    if (!$result) {
        return $response->withStatus(404);
    }

    return $response->withJson($result);
});

$app->run();
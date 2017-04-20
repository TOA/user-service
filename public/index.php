<?php

require '../vendor/autoload.php';

use App\Repository\UserRepository;
use Slim\Http\Request;
use Slim\Http\Response;

$config = [];
$config['displayErrorDetails'] = getenv('APP_ENV');

$app = new Slim\App([
    'settings' => $config
]);

/**
 * Authenticate
 */
$app->put('/user', function (Request $request, Response $response) {
    $email = $request->getAttribute('email');
    $password = $request->getAttribute('password');

    $repository = new UserRepository();
    $user = $repository->findByCredentials($email, $password);

    if (!$user) {
        return $response->withStatus(404);
    }

    return $response->withJson($user->getId());
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

    $repository = new UserRepository();
    $user = $repository->findById($id);

    if (!$user) {
        return $response->withStatus(404);
    }

    return $response->withJson($user->toArray());
});

$app->run();
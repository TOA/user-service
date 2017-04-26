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
$app->post('/user', function (Request $request, Response $response) {
    $email = $request->getParsedBodyParam('email');
    $password = $request->getParsedBodyParam('password');

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
$app->put('/user', function (Request $request, Response $response) {
    $name = $request->getParsedBodyParam('name');
    $email = $request->getParsedBodyParam('email');
    $password = $request->getParsedBodyParam('password');

    $repository = new UserRepository();
    $user = $repository->insert([
        'name' => $name,
        'email' => $email,
        'password' => $password,
    ]);

    if (!$user) {
        return $response->withStatus(500);
    }

    return $response->withJson($user->getId());
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
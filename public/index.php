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

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

/**
 * Authenticate
 */
$app->post('/user', function (Request $request, Response $response) {
    $email = $request->getParsedBodyParam('email');
    $password = $request->getParsedBodyParam('password');

    $repository = new UserRepository();
    $user = $repository->findByEmail($email);

    if (!$user) {
        return $response->withStatus(500);
    }

    if (password_verify($password, $user->getPassword()) === false) {
        return $response->withStatus(401);
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
        'password' => password_hash($password, PASSWORD_BCRYPT),
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
        return $response->withStatus(500);
    }

    return $response->withJson($user->toArray());
});

/**
 * Live Search
 */
$app->get('/search/live', function (Request $request, Response $response) {
	$query = $request->getQueryParam('q');

	if (!$query) {
		return $response->withStatus(400);
	}

	$repository = new UserRepository();
	$results = $repository->liveSearch($query);

	if ($results === false) {
		return $response->withStatus(500);
	}

	return $response->withJson($results->toArray());
});

$app->run();
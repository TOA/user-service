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

    $db = new PDO('mysql:dbname=bagtag;host=127.0.0.1');
    $result = $db->exec("SELECT id FROM `user` WHERE email=$email AND password=$password");

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

    $db = new PDO('mysql:dbname=bagtag;host=127.0.0.1');
    $result = $db->exec("INSERT INTO `user` (`name`, email, password) VALUES ($name, $email, $password");

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

    $db = new PDO('mysql:dbname=bagtag;host=127.0.0.1');
    $result = $db->exec("SELECT email, `name` FROM `user` WHERE id=$id");

    if (!$result) {
        return $response->withStatus(404);
    }

    return $response->withJson($result);
});

$app->run();
<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(dirname(__DIR__).'/.env');

$application = new \App\Application(true);
$route = $application->getControllerByRequest();

if (null !== $route) {
    $controller = new $route[0]($application->getEntityManager());
    echo $controller->{$route[1]}();
} else {
    header("HTTP/1.0 404 Not Found");
    echo 'The requested path is not found. Please check the URL.';
}


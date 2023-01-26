<?php

require_once __DIR__ . '/vendor/autoload.php';

use Links\App\Router;

$router = new Router();
$router->addRoute('/', 'main.twig');
$router->addRoute('/create', 'link.twig');
$router->addRoute('/site', '');

$router->route('/' . key($_GET));

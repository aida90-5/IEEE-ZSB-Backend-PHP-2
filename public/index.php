<?php
session_start();
const BasePath = __DIR__ .'/../';
require BasePath . 'Core/functions.php';
spl_autoload_register(function ($class) {
    //Core\Database
    $result= str_replace('\\',DIRECTORY_SEPARATOR,$class);
    dd($result);
require basePath ("{$result}.php");
});

require basePath('bootstrap.php');

$router =new \core\Router();
$router =require basePath('routes.php');
$uri =parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
$router->route($uri,$method);


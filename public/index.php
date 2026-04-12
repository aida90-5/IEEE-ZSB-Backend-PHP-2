<?php

use Core\Session;

session_start();
const BasePath = __DIR__ .'/../';
require BasePath.'vendor/autoload.php';

session_start();

require BasePath . 'Core/functions.php';

/*spl_autoload_register(function ($class) {
    //Core\Database
    $result= str_replace('\\',DIRECTORY_SEPARATOR,$class);
    dd($result);
require basePath ("{$result}.php");
});*/

require basePath('bootstrap.php');

$router =new \Core\Router();
$router =require basePath('routes.php');
$uri =parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
try {
    $router->route($uri, $method);
}catch(\core\validationException $exception){
    session::flash('errors',$exception->errors);
    session::flash('errors',$exception->old);

    return redirect($router->previousUrl());

}
unset($_SESSION['_flashed']);

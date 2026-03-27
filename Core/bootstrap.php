<?php

use core\App;
use core\container;
use core\database;
$container= new container();
$container->bind('core\Database',function (){
    $config = require basePath('config.php');

    return new Database($config['database']);
});
$db=$container->resolve('core\Database');
dd($db);
App::setContainer($container);
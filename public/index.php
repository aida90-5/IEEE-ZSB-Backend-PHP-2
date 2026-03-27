<?php
const BasePath = __DIR__ .'/../';
require BasePath. 'functions.php';
spl_autoload_register(function ($class) {
require BasePath. ($class) . '.php';)
});
require BasePath. 'router.php';



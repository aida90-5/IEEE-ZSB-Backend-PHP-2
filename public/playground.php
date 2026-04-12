<?php

require __DIR__ .'/../vendor/autoload.php';

$numbers =new \Illuminate\Support\Collection([
 1,2,3,4,5,6,7,8,9
]);

$lessthanorequalto5=$numbers->filter(function($number){
    return $number <= 5;
});
var_dump($lessthanorequalto5);
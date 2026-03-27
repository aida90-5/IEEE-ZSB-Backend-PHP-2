<?php
namespace core\middleware;
class guest
{
public function handle()
{

        if($_SESSION['user'] ?? false) {
            header('location:/');
            exit();
        }
    }

}
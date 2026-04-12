<?php

namespace core\middleware;

use core\Database;
use mysql_xdevapi\Session;

class Authenticator
{
 public function attempt($mail, $password)
 {
     $user =App::resolve(Database::class)->query('select * from users where email=:email',
         ['email'=>$email])->find();
     if($user)
     {
         if(password_verify($password,$user['password']))
         {
             $this->login(
                 ['email'=>$email,]
             );
             return true;
         }
     }
     return false;

 }
 }
 }

public function login($user)
{
    $_SESSION['user']=[
        'email'=>$user['email'],
    ];
    session_regenerate_id(true);
}
public function logout()
{
    Session::destroy();
}
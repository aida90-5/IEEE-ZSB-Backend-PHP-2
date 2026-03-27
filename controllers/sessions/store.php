<?php
use core\App;
use core\Database;
use core\validator;
$db=App::resolve(Database::class);

$email=$_POST['email'];
$password=$_POST['password'];
$errors=[];
if (!validator::email($email))
{
    $errors['email']="Please provide a valid email address";
}
if (!validator::string($password,7,255))
{
    $errors['password']="Please provide a password at least 7 characters long";
}
if(!empty($errors))
{
    return view('sessions/create.view.php'
        ,['errors'=>$errors]);
}
$user =$db->query('select * from users where email=:email',
['email'=>$email])->find();
if($user)
{
    if(password_verify($password,$user['password']))
    {
        login(
            [
                'email'=>$email,
            ]
        );
        header('location:/');
        exit();
    }
}
return view('sessions/create.view.php'
    ,['errors'=>"No matching Account for the email & password"]);
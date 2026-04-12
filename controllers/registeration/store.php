<?php
use core\validator;
use core\App;
use core\Database;
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
    return view('registeration/create.view.php'
        ,['errors'=>$errors]);
}
$user= App::resolve(Database::class);
$result =$user->query('Select * from users where email=:email',
    ['email'=>$email])->find();
if($user){
    header('location:/');
    exit();

}else{
    $db->query('Insert into users (email, password) values (:email, :password)',[
        'email'=>$email,
        'password'=>password_hash($password ,PASSWORD_BCRYPT)
    ]);
    login($user);
    header('location:/');
    exit();
}
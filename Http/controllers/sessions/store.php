<?php
use core\middleware\Authenticator ;
use Http\forms\Loginform;


$form = Loginform::validate($attributes = [
    'email' => $_POST['email'],
    'password' => $_POST['password']
        ]);
}  $signedIn =(new Authenticator())->attempt($attributes['email'],$attributes ['password']) ;

           if(!$signedIn) {
               $form->error('email', 'No matching Account for the email & password')->throw();

           }

redirect('/');




    /*return view('sessions/create.view.php'),[
        'errors' => $form->errors()

}
       return view('sessions/create.view.php'
            ,['errors'=>
                ['email'=>"No matching Account for the email & password"]]);*/





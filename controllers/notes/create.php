<?php
$config = require basePath('config.php');
$db= new Database($config['database']);
$heading ='Create Note';
if($_SERVER ['REQUEST_METHOD'] == 'POST'){
    $errors =[];
    $validator =new validator();
    if(! validator ::string($_POST['body'],1,1000){
    $errors=['body']='A Body of no more than 1000 char is required.';

}
if(! validator ::email($_POST['email'])){
    dd('Please enter a valid email address.');
}

  if(empty($errors)){

    $db->query('Insert Into notes(body,user_id)values(:body,:user_id)')
    [
    ':body'=>$_POST['body'],
    ':user_id'=>$_POST['user_id'];
};
}

<?php
view("notes/create.view.php",[
    'heading'=> 'Create Notes',
    'errors'=> $errors,
]);

<?php
$config = require BasePath('config.php');
$db = new Database($config['database']);

$heading = 'Note';
$currentUserId =1;

$note = $db->query('SELECT * FROM notes WHERE user_id = :user and id = :id',
    ['user' =>1,
        'id' => $_GET['id']
    ])->findorfail();
authorize();
if($note['user_id'] === $currentUserId){
    abort(Responses::Forbidden);
}
<?php
view("notes/index.view.php",[
    'heading'=> 'My Notes',
    'notes' => $note,
]);

<?php
use core\App;
use core\Database;
db=App::resolve(Database::class);

$currentUserId =1;


$note = $db->query('SELECT * FROM notes WHERE id = :id',[
    'id' => $_GET['id']
])->findorfail();
authorize($note['user_id']== $currentUserId);


authorize($note['user_id']== $currentUserId);


view("notes/edit.view.php",[
    'heading'=> 'Edit Notes',
    'errors'=> []
    'note' => $note
]);

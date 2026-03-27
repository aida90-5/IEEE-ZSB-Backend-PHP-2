<?php

use Core\Database;
use core\App;
$db=App::resolve(Database::class);

$currentUserId =25;


    $note = $db->query('SELECT * FROM notes WHERE id = id',[
        'id' => $_GET['id']
    ])->findorfail();
    authorize($note['user_id']== $currentUserId);


    authorize($note['user_id']== $currentUserId);

    view("notes/show.view.php", [
        'heading' => 'Show notes',
        'notes' => $notes,
    ]);

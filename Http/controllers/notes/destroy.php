<?php

use Core\Database;
use core\App;
//$config = require basePath('config.php');
//$db = new Database($config['database']);
$db=App::resolve(Database::class);
$currentUserId =1;

    $note = $db->query('SELECT * FROM notes WHERE id = id',[
        'id' => $_POST['id']
    ])->findorfail();
    authorize($note['user_id']== $currentUserId);
    $db->query('DELETE FROM notes WHERE id = id', [
        'id' => $_POST['id']
    ]);

    header('Location: /notes/');
    exit();

<?php

use core\Database;
use core\App;
use core\Responses;

$db = App::resolve(Database::class);

$currentUserId = 1;

$note = $db->query('SELECT * FROM notes WHERE id = :id', [
    'id' => $_GET['id']
])->findOrFail();

if ($note['user_id'] !== $currentUserId) {
    abort(Responses::FORBIDDEN);
}

view("notes/show.view.php", [
    'heading' => 'Note',
    'note' => $note,
]);

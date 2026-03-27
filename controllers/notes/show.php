<?php
$config = require basePath('config.php');
$db = new Database($config['database']);

$heading = 'Notes';
$notes = $db->query('SELECT * FROM notes WHERE id = 1')->fetchAll();
dd('notes');
<?php
view("notes/show.view.php",[
    'heading'=> 'Show notes',
    'notes' => $notes,
]);

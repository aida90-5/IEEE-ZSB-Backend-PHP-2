<?php require basePath('functions.php')?>
<?php require basePath('Core.php')?>
<?php require basePath('Database.php')?>

<main>
 <div class ="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
     <p>Hello .<?= $_SESSION['user']['email'] ?? 'guest' ?></p>
 </div>
</main>
$db = new Database($config['db']);

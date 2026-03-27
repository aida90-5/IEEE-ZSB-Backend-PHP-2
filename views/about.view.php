<?php
require_once dirname(__DIR__) . '/functions.php';
$heading = isset($heading) ? $heading : 'About Us';
require __DIR__ . '/partial/head.php';
?>

<?php require __DIR__ . '/partial/nav.php'; ?>

<?php require __DIR__ . '/partial/banner.php'; ?>


<main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <p>You are on About Us Page!</p>
        </div>
    </main>

<?php require __DIR__ . '/partial/footer.php'; ?>

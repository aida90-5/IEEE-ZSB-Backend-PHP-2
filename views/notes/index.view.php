<?php require ('core/functions.php'); ?>
<?php require('views/partial/head.php'); ?>
<?php require('views/partial/nav.php'); ?>
<?php require('views/partial/banner.php'); ?>

$heading = isset($heading) ? $heading : 'Home';


<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <ul>
        <?php foreach ($notes as $note) : ?>
        <li>
            <a href="/note?id=1" class="text-blue-500 hover:underline"></a>
            <?= $note['body'] ?> </li>
        <?php endforeach; ?>
      </ul>
        <p class="mt-6">
            <a href="/note/create" class="text-blue-500 hover:underline">Create Note</a>
        </p>
    </div>
</main>

<?php require('views/partial/footer.php'); ?>

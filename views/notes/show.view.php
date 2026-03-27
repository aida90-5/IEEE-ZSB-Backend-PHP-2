<?php require ('core/functions.php');?>
<?php require('views/partial/head.php'); ?>
<?php require('views/partial/nav.php'); ?>
<?php require('views/partial/banner.php'); ?>

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <p class="mb-6">
                <a href="/notes" class="text-blue-500 hover:underline">Go Back</a>
            </p>

            <p>
                <?= htmlspecialchars($note['body']) ?>
            </p>
<a href="/note/edit ?id=<?= $note['id']?>" class="text-grey-500 border border-current px-4 py-1 rounded">Edit</a>
            <form class="mt-6" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="id" value="<?= $note['id'] ?>">

                <button class="text-sm text-red-500 hover:text-red-700 font-medium">
                    Delete Note
                </button>
            </form>
        </div>
    </main>

<?php require('views/partial/footer.php'); ?>
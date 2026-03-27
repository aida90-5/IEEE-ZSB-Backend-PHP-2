<?php require ('core/functions.php'); ?>
<?php require('views/partial/head.php'); ?>
<?php require('views/partial/nav.php'); ?>

    $heading = isset($heading) ? $heading : 'Home';


    <main>

        <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company" class="mx-auto h-10 w-auto" />
                <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-white">Register for a new account</h2>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form action="/register" method="POST" class="space-y-6">
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <div class="mt-2">
                            <input id="email" type="email" name="email" required autocomplete="email" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                        </div>
                    </div>

                    <?php if (isset($errors['email'])) : ?>
                        <p class="text-red-500 text-xs mt-2"><?= $errors['email'] ?></p>
                    <?php endif; ?>

                    <label for="password" class="sr-only">Password</label>

                        <div class="mt-2">

                            <input id="password" type="password" name="password" required autocomplete="current-password" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                        </div>
                    </div>

            <?php if (isset($errors['password'])) : ?>
                <p class="text-red-500 text-xs mt-2"><?= $errors['password'] ?></p>
            <?php endif; ?>
                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"> Register </button>
                    </div>
                </form>

            </div>
        </div>

    </main>

<?php require('views/partial/footer.php'); ?>
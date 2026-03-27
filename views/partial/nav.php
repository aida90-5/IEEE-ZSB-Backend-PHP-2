<nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-8 w-8" src="https://tailwindui.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
                </div>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="/" class="<?= urlIs('/') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium">Home</a>
                        <a href="/about" class="<?= urlIs('/about') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium">About</a>

                        <?php if ($_SESSION['user'] ?? false) : ?>
                            <a href="/notes" class="<?= urlIs('/notes') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium">Notes</a>
                        <?php endif; ?>

                        <a href="/contact" class="<?= urlIs('/contact') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> rounded-md px-3 py-2 text-sm font-medium">Contact</a>
                    </div>
                </div>
            </div>

            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <?php if ($_SESSION['user'] ?? false) : ?>
                        <div class="flex items-center gap-4">
                            <span class="text-white text-sm">Hi, <?= htmlspecialchars($_SESSION['user']['email']) ?></span>

                            <form method="POST" action="/session">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    <?php else : ?>
                        <div class="flex items-center gap-4">
                            <a href="/register" class="<?= urlIs('/register') ? 'bg-white text-gray-900' : 'text-white border border-white hover:bg-gray-100 hover:text-gray-900' ?> rounded-md px-4 py-2 text-sm font-medium transition-colors">
                                Register
                            </a>
                            <a href="/login" class="<?= urlIs('/login') ? 'text-white' : 'text-gray-300 hover:text-white' ?> text-sm font-medium">
                                Log In
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>

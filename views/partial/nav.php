<nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="flex h-16 items-center justify-between">

            <div class="flex items-center">
                <div class="shrink-0">
                    <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" class="size-8" />
                </div>

                <div class="block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="<?= appUrl('/') ?>" class="<?= urlIs('/') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> rounded-md hover:bg-gray-900 px-3 py-2 text-sm font-medium">
                            Home
                        </a>

                        <a href="<?= appUrl('/about') ?>" class="<?= urlIs('/about') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> rounded-md px-3 py-2 text-sm font-medium hover:bg-white/5 hover:text-white">
                            About
                        </a>

                        <a href="<?= appUrl('/notes') ?>" class="<?= urlIs('/contact') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> rounded-md px-3 py-2 text-sm font-medium hover:bg-white/5 hover:text-white">
                            Notes
                        </a>
                        <a href="<?= appUrl('/contact') ?>" class="<?= urlIs('/contact') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> rounded-md px-3 py-2 text-sm font-medium hover:bg-white/5 hover:text-white">
                            Contact
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e" class="size-8 rounded-full" />
            </div>

        </div>

    </div>
</nav>

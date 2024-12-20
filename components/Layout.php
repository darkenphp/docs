<?php

use Build\components\Menu;

$layout = new class {
  #[\Darken\Attributes\Param]
  public $title;

  #[\Darken\Attributes\Slot]
  public $content;

  public function getYear(): int
  {
    return date('Y');
  }
};
?>
<html class="theme-dark" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $layout->title; ?></title>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet" />
    <link href="./assets/output.css" rel="stylesheet">
  </head>
  <body>
    <div
      class="flex h-screen bg-gray-50 dark:bg-gray-900">
      <aside
        class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0">
        <div class="py-4 text-gray-500 dark:text-gray-400">
          <a
            class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200"
            href="#">
            Darken Docs
          </a>
          <ul class="mt-8">
            <?= new Menu('Home', '/'); ?>
            <?= new Menu('Tailwind', '/tailwind'); ?>
          </ul>
        </div>
      </aside>
      <div class="flex flex-col flex-1 w-full">
        <header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">
          <div
            class="container flex items-center justify-between h-full px-6 mx-auto text-purple-600 dark:text-purple-300">
            <div class="flex justify-center flex-1 lg:mr-32">
              <div
                class="relative w-full max-w-xl mr-6 focus-within:text-purple-500">
                <div class="absolute inset-y-0 flex items-center pl-2">
                  <svg
                    class="w-4 h-4"
                    aria-hidden="true"
                    fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                      fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"></path>
                  </svg>
                </div>
                <input
                  class="w-full pl-8 py-2 pr-2 text-sm text-gray-700 placeholder-gray-600 bg-gray-100 border-0 rounded-md dark:placeholder-gray-500 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:placeholder-gray-500 focus:bg-white focus:border-purple-300 focus:outline-none focus:shadow-outline-purple form-input"
                  type="text"
                  placeholder="Search for projects"
                  aria-label="Search" />
              </div>
            </div>
          </div>
        </header>
        <main class="h-full overflow-y-auto">
          <div class="container p-6 mx-auto grid text-white">
            <?= $layout->content; ?>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
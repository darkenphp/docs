<?php

use Build\components\Menu;
use Build\components\MenuMore;

$layout = new class {
  #[\Darken\Attributes\ConstructorParam]
  public $title;

  #[\Darken\Attributes\Slot]
  public $content;

  public function getYear(): int
  {
    return date('Y');
  }

  public function nav() : array
  {
    return [
      'Home' => '/',
      'Compile Concept' => '/compile',
      'Extend Runtime' => '/runtime',
      'Tailwind' => '/tailwind',
      'FrankenPHP' => '/frankenphp',
    ];
  }
};
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($layout->title); ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="./assets/output.css" rel="stylesheet">
  </head>
  <body>
    <div class="flex h-screen">
      <!-- Mobile menu toggle button -->
      <input type="checkbox" id="menu-toggle" class="hidden peer">

      <!-- Sidebar -->
      <div class="hidden peer-checked:flex md:flex flex-col w-64 bg-darken transition-all duration-300 ease-in-out">
        <a href="/" class="flex items-center justify-between h-16 px-6">
          <span class="text-white font-bold uppercase">Darken Docs</span>
          <label for="menu-toggle" class="text-white cursor-pointer">
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              class="h-6 w-6 lg:hidden" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12" 
              />
            </svg>
          </label>
</a>
        <div class="flex flex-col flex-1 overflow-y-auto">
          <nav class="flex-1 px-2">
            <?php foreach ($layout->nav() as $name => $url) : ?>
              <?= new Menu($name, $url) ?>
            <?php endforeach; ?>
          </nav>
        </div>
      </div>

      <!-- Main content -->
      <div class="flex flex-col flex-1 overflow-y-auto">
        <!-- Sticky Header -->
        <header class="sticky top-0 z-50 border-b  bg-darkgrey text-white">
          <div class="flex items-center justify-between py-3 px-4">
            
            <!-- Left Side: Breadcrumb + Mobile Menu Toggle -->
            <div class="flex items-center space-x-3">
              <!-- Mobile Menu Toggle -->
              <label for="menu-toggle" class="md:hidden p-2 rounded focus:outline-none cursor-pointer">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                </svg>
              </label>

            </div>

            <!-- Center: Search Bar (hidden on small screens) -->
            <div class="hidden md:flex flex-1 justify-center px-4">
              <form class="relative w-full max-w-sm" method="get" action="/search">
                <input
                  type="search"
                  name="query"
                  placeholder="Search..."
                  class="w-full pl-3 pr-10 py-2 rounded border border-grey text-black
                        focus:outline-none focus:ring focus:ring-primary transition"
                />
                <button
                  type="submit"
                  class="absolute top-1/2 right-3 -translate-y-1/2 text-grey hover:text-white"
                  aria-label="Search"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M21 21l-4.35-4.35m0 0A7.481 7.481 0 014 10.25a7.48 7.48 0 014.35-6.5 
                          7.48 7.48 0 016.5 4.35 7.48 7.48 0 010 9 
                          7.48 7.48 0 01-6.5 4.35z"
                    />
                  </svg>
                </button>
            </form>
            </div>

            <!-- Right Side: Icons / Links -->
            <div class="flex items-center space-x-4">
              <a
                href="https://github.com/darkenphp/framework"
                class="flex items-center space-x-1 hover:text-grey"
              >
                <!-- GitHub Icon -->
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    d="M12 .297c-6.63 0-12 5.373-12 12 
                      0 5.303 3.438 9.8 8.205 11.385 
                      .6.113.82-.258.82-.577 0-.285-.01-1.04-.016-2.04 
                      -3.338.724-4.042-1.61-4.042-1.61 
                      -.546-1.387-1.333-1.758-1.333-1.758 
                      -1.089-.744.083-.73.083-.73 
                      1.205.084 1.84 1.236 1.84 1.236 
                      1.07 1.835 2.809 1.304 3.495.997 
                      .108-.776.418-1.304.76-1.603 
                      -2.665-.305-5.466-1.332-5.466-5.93 
                      0-1.31.469-2.38 1.235-3.22 
                      -.123-.304-.535-1.527.117-3.176 
                      0 0 1.008-.322 3.301 1.23 
                      .957-.266 1.983-.399 3.003-.404 
                      1.02.005 2.047.138 3.006.404 
                      2.291-1.552 3.298-1.23 3.298-1.23 
                      .653 1.649.241 2.872.118 3.176 
                      .77.84 1.234 1.91 1.234 3.22 
                      0 4.61-2.807 5.624-5.479 5.92 
                      .43.372.81 1.102.81 2.222 
                      0 1.606-.014 2.903-.014 3.296 
                      0 .321.217.694.824.576 
                      A11.996 11.996 0 0024 12.297 
                      c0-6.627-5.373-12-12-12z"
                  ></path>
                </svg>
              </a>
            </div>
          </div>
        </header>

        <div class="container mx-auto">
          <div class="p-4 my-4 md">
            <?= $layout->content; ?>
          </div>
        </div>
      </div>
    </div>

    <script type="module">
    // 1) Import Shiki from a CDN (specify the version you want)
    import { codeToHtml } from 'https://esm.sh/shiki@1.0.0'
    // or
    // import { codeToHtml } from 'https://esm.run/shiki@1.0.0'

    // 2) Find all <code> elements that have a class starting with "language-"
    const codeBlocks = document.querySelectorAll('code[class^="language-"]')

    // 3) Highlight them!
    // Because codeToHtml is async, we can use a small async function or Promise.all
    ;(async () => {
      for (const block of codeBlocks) {
        // Extract the language (e.g. "php" from "language-php")
        const langClass = Array.from(block.classList).find(cls => cls.startsWith('language-'))
        const lang = langClass.replace('language-', '')

        // Grab the code from inside the <code> element
        const code = block.textContent

        // Generate highlighted HTML
        const highlighted = await codeToHtml(code, {
          lang,
          theme: 'rose-pine' // or your preferred theme
        })

        // Replace the old <pre><code> with the new highlighted HTML
        // NOTE: If your code is wrapped in a <pre>, you might want to replace the parent node’s innerHTML.
        //       If it’s only <code> without a <pre>, you can directly replace block.outerHTML.
        block.parentNode.innerHTML = highlighted
      }
    })()
  </script>
  </body>
</html>
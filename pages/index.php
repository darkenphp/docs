<?php
use Build\components\Layout;
?>
<?php $layout = (new Layout('Docs'))->openContent(); ?>


# Darken PHP: The Framework with a Twist

Welcome to **Darken PHP**, where we decided to blend the best parts of frameworks like Nuxt.js and Astro into a single *PHP-based* system—minus the node_modules meltdown, of course.

## 1. Introduction

**Darken** is a component-based system: you can write your controller code, view code, and logic all in one tidy file if you wish. Think of it as a streamlined marriage between Nuxt (for the component approach) and Astro (for the single-file concept), but implemented in pure PHP, *and with zero baggage from Node.js package mania*. Composer can still come to the party, but it’s more like your cool uncle than an overstaying houseguest.

<details>
<summary>**Why “Darken”?**</summary>
We wanted a name that suggests a dash of mystery, but it’s really just so you can feel edgy while writing simpler PHP.
</details>

## 2. The Minimalist’s Dream

### 2.1 No Extra Junk
When you spin up a new Darken project, you’ll notice something peculiar—it’s basically empty. Gone are the days of generating a “skeleton app” packed with half a dozen folders (that you never use) and monstrous configuration files (that you’re too afraid to touch).

Darken’s default project structure is intentionally minimal:
- **A single `public` folder** to serve your app.
- **A “components” folder** to house your building blocks.
- **One or two config files** for environment setup.
- That’s it.

You can then add only what you actually need. If your project is small, keep it small. If it’s large, extend it gracefully.

### 2.2 Single-File Components
A typical Darken file might look like this:

```php
<\?php

$layout = new class {
  #[\Darken\Attributes\Param]
  public $title = "Hello World!";

  #[\Darken\Attributes\Slot]
  public $content;

  public function greet()
  {
    return "Hey there from Darken!";
  }
};
?>
```

Notice how everything’s in one place: the “controller” logic at the top, and the “view” below it. You don’t have to jump between multiple files to figure out how data flows.

3. Core Concepts
3.1 Components with Slots
If you’ve ever used Vue, Svelte, or Astro, you’ll love this: slots let you pass different chunks of content into your component. All you do is mark them with an attribute like #[\Darken\Attributes\Slot] and then fill them at render time.

Example:

```php
$alert = new class {
  #[\Darken\Attributes\Param]
  public $type = 'info';

  #[\Darken\Attributes\Slot]
  public $message;
};
```
You can pass the message slot directly when you create the component. Darken will compile and load it just like a standard PHP class.

3.2 Compile Once, Deploy Anywhere
Darken isn’t a wizard that auto-magically conjures new code on every request. Instead, it compiles while you work—or at least before you deploy. This means:

Faster production performance: no overhead from dynamic compilation.
IDE-friendly: everything is straightforward, auto-completes nicely, and you can see exactly what’s going on under the hood.
3.3 Respecting Composer
Yes, we love Composer—“the classy uncle,” if you will. While we avoid Node’s “1,000,000 dependencies” chaos, Composer is an optional tool for installing stable PHP packages as needed. Whether you need routing libraries, database migrations, or PDF generation, you can keep it all under one standard manager.

4. Why Darken Over “Traditional” Config + Controller + View?
Fewer Moves, Less Confusion
Instead of opening config/app.php, Controller/HomeController.php, and views/home.blade.php, your logic and markup can coexist in the same file. Fewer places to dig around means less confusion.

Faster Prototyping
Need a quick page or admin panel? Slap everything together in one single-file component. If it grows big, you can refactor into smaller sub-components. No big re-architecture needed.

Clean Project Layout
Other frameworks often generate boilerplate with 10 different folders. Darken starts minimal. You grow it how you see fit.

No Node.js Overhead
Tired of waiting for NPM to install 600MB of node_modules for a small side project? With Darken, you can skip Node altogether or just keep a minimal build script for CSS (like Tailwind).

IDE Autocompletion
Every Darken property and method is typed, meaning your favorite IDE (VS Code, PHPStorm, etc.) can help you out. No more guesswork.

5. Example Project Flow
Create a file Home.php in your project’s root or “components” folder.
Define your class logic (attributes, methods) and HTML (or partial HTML) in one file.
Include that file wherever you want the component rendered.
Watch Darken compile and produce a final, optimized PHP output automatically (or run a darken build command if that’s how your setup is configured).
That’s it. You don’t have to dance with three different sets of MVC layers for each small feature.

6. Roadmap & Extras
Custom CLI: Darken is aiming to have commands for scaffolding new components, running dev servers, and building for production.
Ecosystem: Because it’s just PHP, you can seamlessly pull in any libraries from Packagist.
Integrations: Future “official” add-ons might include DB wrappers, routing helpers, and more.
7. Conclusion
Darken is built for simplicity and speed—both in how fast you can get features out the door and how quickly it runs in production. No more rummaging through a junk drawer of files, no more Node-based meltdown, just you, your IDE’s auto-complete, and some good old-fashioned PHP.

Ready to give Darken a spin?

Grab the minimal project starter.
Write your first single-file component.
Let Darken handle the rest.
And that’s the gist of it: a handy, minimalist, “Nuxt meets Astro in PHP” approach, minus the overhead. Happy coding!

<?= $layout->closeContent(); ?>
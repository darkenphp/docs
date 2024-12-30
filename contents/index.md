# A Framework with a Twist

Darken PHP merges the best of frameworks like Nuxt.js and Astro into a PHP-based system without the overhead of Node.js dependencies. It’s designed for simplicity, efficiency, and minimalism, offering a unique single-file component approach.

### Single-File Components

```php
<?php
$page = new class {

    #[\Darken\Attributes\RouteParam]
    public string $name;

    public function getGreeting(): string
    {
        return 'Hello, ' . ucfirst($this->name) . '!';
    }
};
?>
<h1><?= $page->getGreeting(); ?></h1>
```

- Combine controller logic, view code, and data flow in one file.
- Simplified structure for faster prototyping and easier maintenance.

### Minimalistic Project Structure

- Starts with just a `public` folder, a `components` folder, and minimal configuration.
- No unnecessary boilerplate—grow your project as needed.

### Components with Slots

- Reusable components with slot functionality, inspired by Vue and Svelte.
- Flexible content management directly in PHP.

### Streamlined Workflow

- No jumping between config, controller, and view files—keep everything in one place.
- Faster prototyping with easy refactoring into smaller components as needed.

## Why Choose Darken?

- **Simplicity**: Start small and extend your project gracefully.
- **Efficiency**: No unnecessary dependencies or bloated setup.
- **Familiarity**: PHP developers can leverage tools like Composer with ease.
- **Performance**: Optimized for production with minimal runtime overhead.

Darken PHP is ideal for developers seeking a lightweight, modern framework with a clean and efficient structure.
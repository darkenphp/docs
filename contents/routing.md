---
title: Routing
description: Define routes and handle requests.
---

# Enable Pages

Routing is only available if you have `pages`. Pages are components that are rendered as full pages using PSR-7 HTTP request and response objects, which can be intercepted by a middleware stack. In order to enable routing, your configuration must implement the `Darken\Config\PagesConfigInterface` which at the same time defines the folder where your pages are located.

## Routing

The routing is strongly inpsired by [Astro's Routing](https://docs.astro.build/en/guides/routing/), its also file based. Which means you define the routes trough the files in the `pages` folder. So if you add a page `hello.php` in the `pages` folder, you can access it via `http://localhost:8009/hello`. This also means that you can define nested routes by creating a folder with the same name as the route and adding an `index.php` file in it. For example, if you create a folder `about` in the `pages` folder and add an `index.php` file in it, you can access it via `http://localhost:8009/about`.

## Home

The `index.php` file in the `pages` folder is the home page. So if you add a `index.php` file in the `pages` folder, you can access it via `http://localhost:8009`, which is the home page. This also works in nested routes, so if you create a folder `about` in the `pages` folder and add an `index.php` file in it, you can access it via `http://localhost:8009/about`.

## Route Parameters

The routing system also supports route parameters, those are declared with `[[` and `]]` in the file name. For example, if you create a file `hello-[[name]].php` in the `pages` folder, you can access it via `http://localhost:8009/hello-world`. This also works with folders, so if you create a folder `about` in the `pages` folder and add a `[[name]].php` file in it, you can access it via `http://localhost:8009/about/darken`. Those route paramters can the be retrieve with the `Darken\Attributes\RouteParam` attribute.

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

## Wildcards

Its very common to catch everything inside a folder (or the root) unless there is an exact match page available, therfore you can add `...` 3 dots to the route parameter. For example, if you create a file `[[...name]].php` in the `pages` folder, you can access it via `http://localhost:8009/hello/world`. This also works with folders, so if you create a folder `about` in the `pages` folder and add a `[[...name]].php` file in it, you can access it via `http://localhost:8009/about/darken/developer`.
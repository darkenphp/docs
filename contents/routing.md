---
title: Routing
description: Define routes and handle requests.
---

# Routing

Routing is available if you have a `pages` directory. Pages are components rendered as full pages using PSR-7 HTTP request and response objects, which can be intercepted by a middleware stack. To enable routing, your configuration must implement the @(Darken\Config\PagesConfigInterface), which also defines the folder where your pages are located.

## File-Based Routing

Routing in Darken is inspired by [Astro's Routing](https://docs.astro.build/en/guides/routing/). It is file-based, meaning you define routes through files in the `pages` folder. For example, if you add a page `hello.php` in the `pages` folder, you can access it at `http://localhost:8009/hello`. You can also define nested routes by creating a folder with the route name and adding an `index.php` file inside it. For example, `pages/about/index.php` is accessible at `http://localhost:8009/about`.

## Home Page

The `index.php` file in the `pages` folder is the home page. So `pages/index.php` is accessible at `http://localhost:8009`. This also works for nested routes, such as `pages/about/index.php` for `http://localhost:8009/about`.

## Route Parameters

The routing system supports route parameters, declared with `[[` and `]]` in the file name. For example, `hello-[[name]].php` in the `pages` folder is accessible at `http://localhost:8009/hello-world`. This also works with folders, so `pages/about/[[name]].php` is accessible at `http://localhost:8009/about/darken`. Route parameters can be retrieved with the @(Darken\Attributes\RouteParam) attribute.

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

You can catch all routes inside a folder (or the root) unless there is an exact match page available by using `...` (three dots) in the route parameter. For example, `[[...name]].php` in the `pages` folder is accessible at `http://localhost:8009/hello/world`. This also works with folders, such as `pages/about/[[...name]].php` for `http://localhost:8009/about/darken/developer`.
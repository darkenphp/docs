---
title: Components
description: Components in Darken PHP.
---

# Components

A component is a reusable piece of code that can be used in multiple pages. Components are classes that can be used in template files to encapsulate logic and make your code more readable and maintainable.

## RouteParam

The @(Darken\Attributes\RouteParam) attribute allows you to bind route parameters directly to properties in your component class. This makes it easy to access dynamic values from the URL.

## Inject

The @(Darken\Attributes\Inject) attribute lets you inject dependencies (like services or database connections) directly into your component properties, making your code cleaner and more testable.

## ConstructorParam

Use @(Darken\Attributes\ConstructorParam) to pass parameters to your component's constructor, allowing for more flexible and configurable components.

When using @(Darken\Attributes\ConstructorParam), the order of constructor parameters is critical for compatibility. If you change the order of properties in your class, the generated constructor signature will also change, which can break existing code. By default, parameters are ordered based on property declaration order, so rearranging properties changes the constructor order. To ensure stable constructor signatures, always specify the `$order` parameter. The `$name` parameter lets you control the name of the constructor parameter (if omitted, the property name is used). The `$order` parameter sets the explicit position in the constructor; parameters with an order are sorted by this value, and others are sorted by declaration order.

For example:

```php
class UserProfile {
    #[ConstructorParam('id', 1)]
    public int $userId;

    #[ConstructorParam('name', 2)]
    public string $username;

    #[ConstructorParam('email', 3)]
    public string $emailAddress;
}
// Always generates: new UserProfile($id, $name, $email)
```

**Tip:** Use explicit `$order` for all constructor parameters to avoid accidental breaking changes when refactoring.

## QueryParam

The @(Darken\Attributes\QueryParam) attribute binds query string parameters from the URL to your component properties.

## PostParam

The @(Darken\Attributes\PostParam) attribute binds POST request data to your component properties, making form handling straightforward.

## Slots

Components can have slots—placeholders in the component that can be filled with content. Slots are defined in the component class and can be filled in the template file.

```php
<?php
use Darken\Attributes\Slot;
$component = new class {
    #[Slot]
    public string $content;
};
?>
<div style="background-color: red;">
    <?= $component->content; ?>
</div>
```

The [Compiler](compile.md) will generate `openContent()` and `closeContent()` methods on the polyfill class. The `openContent()` method opens the slot, and the `closeContent()` method closes it, so everything in between is passed to the `$content` property.

```php
<h1>Hello</h1>
<?php $component = (new Component)->openContent(); ?>
    <p>The slot $content rendered in red</p>
<?= $component->closeContent(); ?>
```
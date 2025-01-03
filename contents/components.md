---
title: Components
description: Components in Darken PHP.
---

# Components

A component is a reusable piece of code that can be used in multiple pages. Components are classes that can be used in the template files. They can be used to encapsulate logic and to make the code more readable and maintainable.

## RouteParam

## Inject

## ConstructorParam

## QueryParam

## PostParam

## Slots

Components can have slots. Slots are placeholders in the component that can be filled with content. Slots are defined in the component class and can be filled with content in the template file.

```php
<?php
use Darken\Attributes\Slot;
$component = new class {
    #[Slot]
    public string $content;
};
?>
<div style="bg-red">
    <?= $component->content; ?>
</div>
```

The [Compiler](compiler) will generate a `openContent()` and `closeContent()` method on the polyfill class. The `openContent()` method will open the slot and the `closeContent()` method will close the slot, so everything in between will be bassed to the `$content` property.

```php
<h1>Hello</h1>
<?php $component = (new Component)->openContent(); ?>
    <p>The slot $content rendered in red</p>
<?= $component->closeContent(); ?>
```
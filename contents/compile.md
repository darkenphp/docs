---
title: Compile
description: How Darken PHP compiles your code.
---

# Compile Concept

> [!IMPORTANT]
> This section is under heavy development and will be updated soon.

Darken PHP compiles your code into a polyfill and a compiled file. The polyfill is a namespaced file that loads the compiled file and renders it. The compiled file contains your original code but with some modifications to make it work with the polyfill.

<pre class="mermaid">
sequenceDiagram
    participant Original Code
    participant Compiled Code
    participant Polyfill
    Original Code-->>Compiled Code: Compile Original Code
    Compiled Code-->>Polyfill: Include Compiled Code in Polyfill
</pre>

This is what the compile process looks like:

<pre class="mermaid">
flowchart TD
 A[Original File] -->  B
 B(Build Process) --> C & D
 C[Compile]
 D[Polyfill]
</pre>

The runtime is loaded in the polyfill, and the compiled file is rendered by the polyfill. This is what the process looks like:

<pre class="mermaid">
flowchart TD
 A[Runtime] -->  B
 B[Polyfill] --> | render | C
 C[Compiled File]
</pre>

To understand how Darken works, you need to understand the concept of compiling.

## Namespaced Files

All components (or pages, which are technically just components) use anonymous classes. This allows you to put logic into the class and directly use it in the template.

**Example File: `pages/test.php`**

```php
$foo = new class {
    public function bar(): string
    {
        return 'baz';
    }
};
?>
<h1><?= $foo->bar(); ?></h1>
```

Since these files are not namespaced, Darken creates a file in the `.build` directory that is resolvable by a namespaced file and loads the original code. The namespaced file is called the *polyfill* file, and the original file is the *compiled* file. This means the above example file `pages/test.php` will be compiled to:

+ polyfill: `.build/pages/test.php` with namespace `Build\pages\test`, class name `test`
+ compiled: `.build/pages/test.compiled.php`

This is what the polyfill file looks like:

```php
<?php
namespace Build\pages;

class test extends \Darken\Code\Runtime
{
    public function renderFilePath(): string
    {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR  . 'test.compiled.php';
    }
}
```

When you now open the namespaced polyfill and render it:

```php
<?= (new Build\pages\test())->render(); ?>
```

> `render()` is defined in the @(Darken\Code\Runtime) and invokes and handles the defined `renderFilePath()` method

The polyfill will be loaded and the compiled file (which contains your original code) will be invoked and rendered. It's important to understand that during the compile process, the original file will be modified, and the @(Darken\Code\Runtime) class will be injected into the class constructor. Since it's loaded in the @(Darken\Code\Runtime) class, we also have access to this object, which allows us to transfer data between the polyfill and the compiled file.

So the compiled code from above would look something like this:

```php
$foo = new class($this) {

    public function __construct(\Darken\Code\Runtime $runtime)
    {
        $this->runtime = $runtime;
    }

    public function bar(): string
    {
        return 'baz';
    }
};
?>
<h1><?= $foo->bar(); ?></h1>
```

This is where the only magic happens. Since you cannot have a constructor in an anonymous class and directly run this object without creating it first, Darken does this during the compile step for you. This is, of course, only needed when you have to interact with the runtime object—for example, getting the router parameters, using Dependency Injection, collecting query params, or handling other runtime-related tasks. Therefore, PHP attributes can be used to describe a property or class, and Darken will inject the correct code during build time.

## Example with Constructor Parameters

To automatically inject a constructor parameter into a class attribute, you can use the @(Darken\Attributes\ConstructorParam) attribute. This ensures that the class generates a constructor where you have to enter a valid, correctly typed parameter:

```php
$obj = new class {
    #[\Darken\Attributes\ConstructorParam(name: 'bar', order: 1)]
    public string $bar;
};
?>
<h1><?= $obj->bar; ?></h1>
```

This will resolve the constructor parameter `bar` from the runtime object and inject it into the class attribute, with perfectly typed properties. To understand how this works, let's take a look at the polyfill code for the above example:

```php
<?php
namespace Build\pages\sub;

class Test extends \Darken\Code\Runtime
{
    public function __construct(string $bar)
    {
         $this->setData('constructorParams', 'bar', $title);
    }

    public function renderFilePath(): string
    {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR  . 'Test.compiled.php';
    }
}
```

Now the compiled code (which is your original code, just modified) will inject the constructor parameter in the constructor. This looks like this (simplified):

```php
$obj = new class($this)
{
    #[\Darken\Attributes\ConstructorParam]
    public string $bar;

    public function __construct(\Darken\Code\Runtime $runtime)
    {
        $this->bar = $runtime->getData('constructorParams', 'bar');
    }
};
<h1><?= $obj->bar; ?></h1>
```

The methods `getData` is provided by the @(Darken\Code\Runtime) class and allow communication between the polyfill and the compiled file, since they share the same @(Darken\Code\Runtime) class.

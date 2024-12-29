# Compile Concept

To understand how Darken works we need to understand the concept of compiling.

All components (or pages, which are technically just components) are using anyonmous classes, which allows you to put logic into the class
but also directly us it in the template.

File: pages/sub/Test.php

```php
$foo = new class {
    public string $bar = 'baz';

    public function largeBar(): string
    {
        return strtoupper($this->bar);
    }
};
?>
<h1><?= $foo->largeBar(); ?></h1>
```

Since those files are not namespaced we need to create a file in the `.build` directly which is resolvable by a namespaced
file and loads the original code. The namespaced file is what we call the *polyfill* file and the original file will be compiled
so name it *compiled* file. This means the above example file `pages/sub/test.php` will be compiled to

+ polyfill: .build/pages/sub/Test.php with namespace `Build\pages\sub\Test` class name `Test`
+ compiled: .build/pages/sub/Test.compiled.php

This is what the pollyfill file looks like:

```php
<?php
namespace Build\pages\sub;

class Test extends \Darken\Code\Runtime
{
    public function renderFilePath(): string
    {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR  . 'Test.compiled.php';
    }
}
```

When you new open the Namespaced polyfill and render it

```php
<?= (new Build\pages\sub\Test())->render(); ?>
```

::: render() is defined in the Darken\Code\Runtime and invokes and handles the defined renderFilePath() method :::

The pollyfill will be loaded and the compiled file (which contains your original code) will be invoked and rendered. Its important
to understand the during the compile process the original file will be modified and the `\Darken\Code\Runtime` class will be injected
into the class constructor since its loaded in the `\Darken\Code\Runtime` class we also have access to this objext which allows us to transfer
data between the polyfill and the compiled file.

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

This is where the only magic happens, since you can not have a constructor in an anonymous class and directly run this object without to create it
first, darken does this at the compile step for you, which is of course only needed when you have to interact with the runtime object, for example
getting the router paramters, using Dependency Injection, Collecting Query params or any other runtime related stuff. Therefore the
PHP Attributes to describe a property or class, and darken will inject the correctl code while build time.

So in order to automataiclly inect a get constructor parameter to a class attribute you can use `\Darken\Attributes\ConstructorParam` attribute, this will ensure the class genreates a constructor you have to enter a valid correctly typed parameter:

```php
$obj = new class {
    #[\Darken\Attributes\ConstructorParam]
    public string $bar;
};
?>
<h1><?= $obj->bar; ?></h1>
```

This will resolve the constructor parameter `bar` from the runtime object and inject it into the class attribute, with perfectly
typed properties.

To understand how this works, lets take a look at the polyfill code for the above example:

```php
<?php
namespace Build\pages\sub;

class Test extends \Darken\Code\Runtime
{
    public function __construct(string $bar)
    {
        $this->setArgumentParam("bar", $bar);
    }

    public function renderFilePath(): string
    {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR  . 'Test.compiled.php';
    }
}
```

now the compiled code will inject the argument param in the constrcutor, this looks like this:

```php
$obj = new class($this)
{
    #[\Darken\Attributes\ConstructorParam]
    public string $bar;

    public function __construct(\Darken\Code\Runtime $runtime)
    {
        $this->runtime = $runtime;
        $this->bar = $this->runtime->getArgumentParam('bar');
    }
};
```

The method `setArgumentParam` and `getArgumentParam` are booth provided by the `\Darken\Code\Runtime` class and allows you to talke between polyfill and compiled, since the are from the same `Darken\Code\Runtime` class.

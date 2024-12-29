# Compile Concept

To understand how Darken works, we need to understand the concept of compiling.

All components (or pages, which are technically just components) use anonymous classes. This allows you to put logic into the class but also directly use it in the template.

File: `pages/sub/Test.php`

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

Since these files are not namespaced, we need to create a file in the `.build` directory that is resolvable by a namespaced file and loads the original code. The namespaced file is what we call the *polyfill* file, and the original file will be compiled—this is called the *compiled* file. This means the above example file `pages/sub/test.php` will be compiled to:

+ polyfill: `.build/pages/sub/Test.php` with namespace `Build\pages\sub\Test`, class name `Test`
+ compiled: `.build/pages/sub/Test.compiled.php`

This is what the polyfill file looks like:

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

When you now open the namespaced polyfill and render it:

```php
<?= (new Build\pages\sub\Test())->render(); ?>
```

> `render()` is defined in the `Darken\Code\Runtime` and invokes and handles the defined `renderFilePath()` method

The polyfill will be loaded, and the compiled file (which contains your original code) will be invoked and rendered. It's important to understand that during the compile process, the original file will be modified, and the `\Darken\Code\Runtime` class will be injected into the class constructor. Since it's loaded in the `\Darken\Code\Runtime` class, we also have access to this object, which allows us to transfer data between the polyfill and the compiled file.

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

To automatically inject a constructor parameter into a class attribute, you can use the `\Darken\Attributes\ConstructorParam` attribute. This ensures that the class generates a constructor where you have to enter a valid, correctly typed parameter:

```php
$obj = new class {
    #[\Darken\Attributes\ConstructorParam]
    public string $bar;
};
?>
<h1><?= $obj->bar; ?></h1>
```

This will resolve the constructor parameter `bar` from the runtime object and inject it into the class attribute, with perfectly typed properties.

To understand how this works, let's take a look at the polyfill code for the above example:

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

Now the compiled code will inject the argument parameter in the constructor. This looks like this:

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

The methods `setArgumentParam` and `getArgumentParam` are both provided by the `\Darken\Code\Runtime` class and allow communication between the polyfill and the compiled file, since they share the same `Darken\Code\Runtime` class.
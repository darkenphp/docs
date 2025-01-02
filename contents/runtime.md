---
title: Runtime
description: Extend the Compiler with a custom runtime.
---

# Extend Compiler with Custom Runtime

> [!IMPORTANT]
> This is very conceptual and not fully implemented yet.

One of the amazing things you can do when all files need to be compiled before running is hook into this compile process. This allows you to perform any sort of magic you want.

A basic example is creating a custom runtime that will be compiled into the final output. This runtime can be used to perform any sort of magic you want.

All you need to do is create your own runtime file.

Runtime: `App\MyRuntime.php`

```php
class MyRuntime extends \Darken\Code\Runtime
{
    public function getUserById(int $id): array
    {
        return $this->db->query('SELECT * FROM users WHERE id = ?', [$id])->fetch();
    }
}
```

::: todo: Where to define `setRuntimeClass`(?...) :::

Configure Darken to use this class while building the project. Now every component (and page, which is technically just a component) will extend this class.

Since all components extend this class, you can listen for any attributes and modify the compiled output to interact with your runtime class.

Create an attribute. For example, we want to create an attribute that automatically resolves a user from a database system.

Attribute: `App\Attributes\FindUser.php`

```php
#[Attribute(Attribute::TARGET_PROPERTY)]
class FindUser {

}
```

Now we can listen for this attribute in our runtime class and resolve the user from the database:

```php
class FindUserHook extends PropertyAttributeHook
{
    public function compileConstructorHook(PropertyAttribute $attribute, ClassMethod $constructor): ClassMethod
    {
        $constructor->stmts[] = $attribute->createAssignExpression('getUserById');
        return $constructor;
    }

    public function isValidAttribute(AttributeExtractorInterface $attribute): bool
    {
        return $attribute->getDecoratorAttributeName() === FindUser::class;
    }

    public function polyfillConstructorHook(PropertyAttribute $attribute, Method $constructor): Method
    {
        $constructor->addParam($this->createParam($attribute->getName(), 'mixed'));

        $constructor->addStmt(new MethodCall(
            new Variable('this'),
            'getUserById',
            [
                new Arg(new String_($attribute->getName())),
                new Arg(new Variable($attribute->getName())),
            ]
        ));

        return $constructor;
    }
}
```

Now, if you use this attribute in any component, it will automatically resolve the user from the database:

```php
$class = new class {
    #[FindUser]
    public array|false $user;
};
```

Internally, this will look like this after compiling:

```php
$class = new class {
    public array|false $user;

    public function __construct(int $id)
    {
        $this->user = $this->getUserById($id);
    }
};
```
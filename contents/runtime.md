
# Extend Compiler with Custom Runtime

one of the amazing things you can do, when all files needs to be compiled before running, you can hook into this compile process, which
allows you to do any sort of magic you want.

A basic example is to create a custom runtime that will be compiled into the final output, this runtime can be used to do any sort of magic you want.

All you need to is to create a own runtime file

Runtime: App\MyRuntime.php

```php
class MyRuntime extends \Darken\Code\Runtime
{
    public function getUserById(int $id): array
    {
        return $this->db->query('SELECT * FROM users WHERE id = ?', [$id])->fetch();
    }
}
```

::: todo: where to defined setRuntimeClass(?...) :::

And configure Darken to use this class while building the project, now every component (and page, which its technigally just a component) will 
extend from this class.

Now since all components are extending from this class, you can listen for any attributes and modified the compiled output, which then interacts with
your runtime class.

Create an attribute, for example we want to create a Attribute which automataiclly resolves the user from an db system:

Attribute: App\Attributes\FindUser.php

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

Now if you use this attribute in any component, it will automatically resolve the user from the database:

```php
$class = new class {
    #[FindUser]
    public array|false $user;
};
```

Internally this will look like this after the compiling

```php
$class = new class {
    public array|false $user;

    public function __construct(int $id)
    {
        $this->user = $this->getUserById($id);
    }
};
```

::: code-group

```js [config.js]
/**
 * @type {import('vitepress').UserConfig}
 */
const config = {
  // ...
}

export default config
```

```ts [config.ts]
import type { UserConfig } from 'vitepress'

const config: UserConfig = {
  // ...
}

export default config
```

:::
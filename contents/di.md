---
title: DI
description: Dependency Injection in Darken PHP.
---

# Dependency Injection

As already mentioned in the [Container](/container) section, the container is the heart of the Framework. It is responsible for managing the dependencies of your application. The container is also responsible for creating the objects and injecting the dependencies into them. This is called Dependency Injection (DI).

## Inject Param

In order to inject a class into your page or component, you can use the @(Darken\Attributes\Inject) attribute, ensure that the class is registered in the container before injecting it.

```php
class Config implements ContainerServiceInterface
{
    public function containers(ContainerService $service): ContainerService
    {
        return $service
            ->register(\App\Db::class, ['dsn' => 'sqlite::memory:']);
    }
}
```

The makes Darken very powerfull since the class to inject (\App\Db in this case) is type hinted in your Component or Page:

```php
$page = new class {
    #[\Darken\Attributes\Inject]
    public \App\Db $db;
};
?>
<p>Configured DSN: <?= $page->db->getDsn(); ?></p>
```
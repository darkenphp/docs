---
title: DI
description: Dependency Injection in Darken PHP.
---

# Dependency Injection

The @(Darken\Service\ContainerService) is a simple dependency injection container that allows you to define services and their dependencies. Its a core concept troughout the whole framework and should be intensivly used by developers, for applications, components and extensions. At any place in Darken it is possible to inject classes defined in the container.

In order to start providing new containers and register them then [Configuration](/config) needes to be extenend using the @(Darken\Service\ContainerServiceInterface) which will look like this:

```php
public function containers(ContainerService $service): ContainerService
{
    return $service
        ->register(Db::class, ['dsn' => 'sqlite::memory:']);
}
```

## Inject Param

The makes Darken very powerfull since the class to inject (\App\Db in this case) is type hinted in your Component or Page:

```php
$page = new class {
    #[\Darken\Attributes\Inject]
    public \App\Db $db;
};
?>
<p>Configured DSN: <?= $page->db->getDsn(); ?></p>
```

## Auto Wiring
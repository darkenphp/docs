---
title: DI Containers
description: Dependency Injection Containers in Darken PHP.
---

# Dependency Injection Containers

The `Darken\Service\ContainerServie` is a simple dependency injection container that allows you to define services and their dependencies. Its a core concept troughout the whole framework and should be intensivly used by developers, for applications, components and extensions. At any place in Darken it is possible to inject classes defined in the container.

In order to start providing new containers and register them then [Configuration](/config) needes to be extenend using the `Darken\Services\ContainerServiceInterface` which will look like this:

```php
public function containers(ContainerService $service): ContainerService
{
    return $service
        ->register(Db::class, ['dsn' => 'sqlite::memory:']);
}
```
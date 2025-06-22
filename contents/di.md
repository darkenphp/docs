---
title: DI
description: Dependency Injection in Darken PHP.
---

# Dependency Injection

@(Darken\Service\ContainerService) is a simple dependency injection container that allows you to define services and their dependencies. This is a core concept throughout the framework and should be used extensively by developers for applications, components, and extensions. Anywhere in Darken, you can inject classes defined in the container.

To provide new containers and register them, extend your [Configuration](/config.md) using @(Darken\Service\ContainerServiceInterface):

```php
public function containers(Darken\Service\ContainerService $service): Darken\Service\ContainerService
{
    return $service
        ->register(Db::class, ['dsn' => 'sqlite::memory:']);
}
```

## Inject Attribute

This makes Darken very powerful, since the class to inject (e.g., `\App\Db`) is type-hinted in your Component or Page:

```php
$page = new class {
    #[\Darken\Attributes\Inject]
    public \App\Db $db;
};
?>
<p>Configured DSN: <?= $page->db->getDsn(); ?></p>
```

## Auto Wiring

Darken supports auto-wiring, so dependencies are automatically resolved and injected based on type hints, reducing boilerplate and making your code cleaner.
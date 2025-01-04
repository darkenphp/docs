---
title: Config
description: Object oriented configuration in Darken PHP.
---

# Configuration

One of the primary goals of the Framework is to make everything available as PHP objects. While this approach might seem complex at first glance, it offers numerous advantages. Configuration is no exception—it is also represented as an object that can be extended and modified. This means your IDE can assist with autocompletion, and you can leverage the full power of PHP to manage your configuration.

## Configuration Object

Every configuration must implement @(Darken\Config\ConfigInterface). For a solid starting point, you can extend @(Darken\Config\BaseConfig).

```php
class Config extends BaseConfig
{
    public function __construct(private readonly string $rootDirectoryPath)
    {
        $this->loadEnvFile();
    }

    public function getRootDirectoryPath(): string
    {
        return $this->path($this->rootDirectoryPath);
    }

    public function getDebugMode(): bool
    {
        return (bool) $this->env('DARKEN_DEBUG', false);
    }

    public function getPagesFolder(): string
    {
        return $this->getRootDirectoryPath() . DIRECTORY_SEPARATOR . $this->env('DARKEN_PAGES_FOLDER', 'pages');
    }

    public function getBuildOutputFolder(): string
    {
        return $this->getRootDirectoryPath() . DIRECTORY_SEPARATOR . $this->env('DARKEN_BUILD_OUTPUT_FOLDER', '.build');
    }

    public function getBuildOutputNamespace(): string
    {
        return $this->env('DARKEN_BUILD_OUTPUT_NAMESPACE', 'Build');
    }

    public function getBuildingFolders(): array
    {
        return [
            $this->getRootDirectoryPath() . DIRECTORY_SEPARATOR . 'components',
            $this->getPagesFolder(),
        ];
    }
}
```

The @(Darken\Config\BaseConfig) class already implements ConfigInterface and includes several useful methods to streamline your configuration. It also implements the @(Darken\Config\PagesConfigInterface), which indicates that the application will use pages. For instance, if you are building an [Extension](/extension), you won't need to implement the PagesConfigInterface.

## Environment Variables

The @(Darken\Config\ConfigHelperTrait) provides helpful methods for working with environment variables. The @(Darken\Config\ConfigHelperTrait::loadEnvFile()) method loads the `.env` file, if it exists in your project's root directory, and populates the $_ENV superglobal. You can then use the @(Darken\Config\ConfigHelperTrait::env()) method to retrieve environment variable values.

## Add your Methods

Representing configuration as an object allows you to create custom methods for retrieving configuration values. These methods can be accessed safely by injecting the configuration object into your classes. 

```php
namespace App;

class Config extends \Darken\Config\BaseConfig
{
    public function getApiToken(): string
    {
        return $this->env('API_TOKEN', '123123123');
    }
}
```

The above example demonstrates how to create a custom method to retrieve an API token from the environment variables. If the API token is not defined in the environment variables, the default value `123123123` will be returned.

Using the configuration object in your classes is straightforward. Simply inject the configuration object into the class constructor and access its methods as needed.

```php
class ApiClient
{
    public function __construct(private \App\Config $config)
    {
        var_dump($this->config->getApiToken());
    }
}
```

This design ensures that your configuration is both flexible and maintainable, leveraging PHP's object-oriented capabilities to their fullest.
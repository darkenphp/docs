---
title: Config
description: Object-oriented configuration in Darken PHP.
---

# Configuration

One of the primary goals of Darken is to make everything available as PHP objects. While this approach might seem complex at first, it offers many advantages. Configuration is no exception—it is also represented as an object that can be extended and modified. This means your IDE can assist with autocompletion, and you can leverage the full power of PHP to manage your configuration.

## Configuration Object

Every configuration must implement @()Darken\Config\ConfigInterface. For a solid starting point, you can extend @()Darken\Config\BaseConfig.

```php
class Config extends Darken\Config\BaseConfig
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

The @()Darken\Config\BaseConfig class already implements @()Darken\Config\ConfigInterface and includes several useful methods to streamline your configuration. It also implements the @()Darken\Config\PagesConfigInterface, which indicates that the application will use pages. For instance, if you are building an [Extension](/extensions.md), you won't need to implement the @()Darken\Config\PagesConfigInterface.

## Environment Variables

You can use environment variables in your configuration by calling `$this->env('VAR_NAME', $default)`. This allows you to easily manage different settings for development, staging, and production environments.

---

> **Tip:** Use configuration objects to keep your application flexible and maintainable. Take advantage of PHP's type system and autocompletion for a better developer experience.
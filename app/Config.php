<?php

namespace App;

use Darken\Config\BaseConfig;
use Darken\Config\ConfigHelperTrait;
use Darken\Config\ConfigInterface;
use Darken\Debugbar\Build\Extension;
use Darken\Debugbar\DebugBar;
use Darken\Debugbar\DebugBarConfig;
use Darken\Events\AfterBuildEvent;
use Darken\Service\ContainerService;
use Darken\Service\ContainerServiceInterface;
use Darken\Service\EventService;
use Darken\Service\EventServiceInterface;
use Darken\Service\ExtensionService;
use Darken\Service\ExtensionServiceInterface;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Files\FileHelper;

/**
 * Config class implementing configuration and dependency injection management.
 * 
 * This class handles application configuration and provides support for registering
 * services for dependency injection. It uses environment variables to customize
 * various aspects of the application setup.
 */
class Config extends BaseConfig implements ContainerServiceInterface, EventServiceInterface, ExtensionServiceInterface
{
    /**
     * Initialize the configuration class and load the environment file.
     */
    public function __construct(private readonly string $rootDirectoryPath)
    {
        $this->loadEnvFile();
    }

    public function extensions(ExtensionService $service): ExtensionService
    {
        $debugbarConfig = (new DebugBarConfig(
                isActive: $this->getDebugMode()
            ))
            ->config($this);

        $debugbar = new Extension($debugbarConfig);

        return $service
            ->register($debugbar);
    }

    /**
     * Register services for dependency injection.
     * 
     * Use this method to register services or objects into the DI container,
     * making them available for injection into other components or pages.
     * 
     * If no services are required, this method and the ContainerServiceInterface
     * can be safely removed.
     */
    public function containers(ContainerService $service): ContainerService
    {
        return $service;
    }

    public function getContentsFilePath() : string
    {
        return $this->getBuildOutputFolder() . DIRECTORY_SEPARATOR . 'contents.json';
    }

    public function events(EventService $service): EventService
    {
        return $service
            ->on(AfterBuildEvent::class, [MarkdownFilesGenerator::class])
            ->on(AfterBuildEvent::class, [PhpDocsGenerator::class]);
    }

    /**
     * Get the root directory path of the application.
     * 
     * Used as the base path for resolving other directories.
     */
    public function getRootDirectoryPath(): string
    {
        return $this->path($this->rootDirectoryPath);
    }

    /**
     * Check if debug mode is enabled.
     * 
     * The debug mode can be toggled via the `DARKEN_DEBUG` environment variable.
     */
    public function getDebugMode(): bool
    {
        return (bool) $this->env('DARKEN_DEBUG', false);
    }

    /**
     * Get the path to the folder containing pages.
     * 
     * The folder location is relative to the root directory and can be customized
     * using the `DARKEN_PAGES_FOLDER` environment variable.
     */
    public function getPagesFolder(): string
    {
        return $this->getRootDirectoryPath() . DIRECTORY_SEPARATOR . $this->env('DARKEN_PAGES_FOLDER', 'pages');
    }

    /**
     * Get the output folder for build artifacts.
     * 
     * The folder is determined relative to the root directory and can be customized
     * via the `DARKEN_BUILD_OUTPUT_FOLDER` environment variable.
     */
    public function getBuildOutputFolder(): string
    {
        return $this->getRootDirectoryPath() . DIRECTORY_SEPARATOR . $this->env('DARKEN_BUILD_OUTPUT_FOLDER', '.build');
    }

    /**
     * Get the namespace for build output.
     * 
     * The namespace can be configured using the `DARKEN_BUILD_OUTPUT_NAMESPACE` environment variable.
     */
    public function getBuildOutputNamespace(): string
    {
        return $this->env('DARKEN_BUILD_OUTPUT_NAMESPACE', 'Build');
    }

    /**
     * Get a list of folders involved in the building process.
     * 
     * Includes folders for components and pages. The paths are resolved relative
     * to the root directory.
     */
    public function getBuildingFolders(): array
    {
        return [
            $this->getRootDirectoryPath() . DIRECTORY_SEPARATOR . 'components',
            $this->getPagesFolder(),
        ];
    }
}

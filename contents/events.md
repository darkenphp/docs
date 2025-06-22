---
title: Events
description: Events in Darken PHP.
---

# Events

Darken provides several events you can listen to, allowing you to hook into the build process or other framework actions. For example, you can add your own build commands to run after the build command completes.

To listen to events, add @(Darken\Service\EventServiceInterface) to your config and use the `on` method:

```php
public function events(EventService $service): EventService
{
    return $service->on(AfterBuildEvent::class, function() {
        $files = FileHelper::findFiles($this->getRootDirectoryPath() . DIRECTORY_SEPARATOR . 'contents', ['only' => ['*.md']]);
        $json = [];
        foreach ($files as $markdown) {
            $json[] = [
                'content' => file_get_contents($markdown),
                'href' => pathinfo($markdown, PATHINFO_FILENAME),
            ];
        }
        file_put_contents($this->getContentsFilePath(), json_encode($json));
    });
}
```

The above example runs on every build and creates a JSON file with all the markdown files in the `contents` directory. This can be used to create a search index or a sitemap.
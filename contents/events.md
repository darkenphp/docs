# Events

There are several events which can be listeneed to, for example you can add your own build commands after build command was run:

Add to your config: `Darken\Service\EventServiceInterface` and use the `on` method to listen to events.

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

The above example will run on every build and will create a JSON file with all the markdown files in the `contents` directory. This can be later used to create a search index or a sitemap.
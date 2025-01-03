---
title: APIs
description: Define APIs and handle requests.
---

# API

Darken has a very strong focus on APIs, it can be used only to create APIs instead of template based Websites. The [Routing](routing) system makes it very easy to define APIs, therefore also large Projects won't get messy.

## Return Response

Compared to a Component or Page, an API only returns the response, this can be done by returning a `Psr\Http\Message\ResponseInterface` object or which the @(Darken\Web\Response) class is a implementation of, and instead of rendering a template, you can directly return the anonymus class. In order to let Darken know that this is an API, you must implement the @(Darken\Code\InvokeResponseInterface) interface.

```php
<?php
use Darken\Web\Response;

return new class implements Darken\Code\InvokeResponseInterface {

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $data = json_encode(['message' => 'Hello, World!']);
        
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
};
```
---
title: APIs
description: Define APIs and handle requests.
---

# APIs

Darken has a strong focus on APIs. You can use it to create APIs instead of template-based websites. The [Routing](routing.md) system makes it easy to define APIs, so even large projects remain organized.

## Returning a Response

Unlike a component or page, an API only returns a response. This can be done by returning a `Psr\Http\Message\ResponseInterface` object, or the @(Darken\Web\Response) class (which is an implementation). Instead of rendering a template, you can directly return an anonymous class. To let Darken know this is an API, implement the @(Darken\Code\InvokeResponseInterface) interface.

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

> **Tip:** Use APIs for headless applications, SPAs, or to provide data to other services.
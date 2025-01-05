---
title: Middleware
description: Handle requests and responses.
---

# Middleware

Middleware are only handled when @(Darken\Config\PagesConfigInterface) is implemented. Middleware are classes that can intercept the request and response objects. They are executed in the order they are added to the middleware stack. Middleware can be used to modify the request and response objects, for example to add headers or to modify the body.

## Global

Global middleware are executed on every request. They are added to the middleware stack in the configuration.

```php
<?php
class Config implements MiddlewareServiceInterface
{
    public function middlewares(MiddlewareService $service): MiddlewareService
    {
        return $service
            ->register([\Darken\Middleware\CorsMiddleware::class, \Darken\Enum\MiddlewarePosition::BEFORE]);
    }
}
?>
```

The middleware must implement the @(Darken\Middleware\MiddlewareInterface) interface.

```php
class CorsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization');
    }
}
```

Example for Authentication middleware.

```php
public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
{
    // Retrieve the authentication header from the request
    $authHeaderValue = $request->getHeaderLine($this->authHeader);

    // Check if the authentication token is present and valid
    if (empty($authHeaderValue) || $authHeaderValue !== $this->expectedToken) {
        // Return a 401 Unauthorized response
        return new Response(
            401,
            ['Content-Type' => 'application/json'],
            json_encode(['error' => 'Unauthorized'])
        );
    }

    // Authentication successful; proceed to the next middleware or handler
    return $handler->handle($request);
}
```

Example to add a header value to the response.

```php
 public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Proceed to the next middleware or handler and get the response
        $response = $handler->handle($request);

        // Add the custom header to the response
        return $response->withHeader($this->name, $this->value);
    }
```

## Page


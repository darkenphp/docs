---
title: Routing
description: Define routes and handle requests with advanced parameter types and HTTP methods.
---

# Routing

Routing is available if you have a `pages` directory. Pages are components rendered as full pages using PSR-7 HTTP request and response objects, which can be intercepted by a middleware stack. To enable routing, your configuration must implement the @(Darken\Config\PagesConfigInterface), which also defines the folder where your pages are located.

## File-Based Routing

Routing in Darken is inspired by [Astro's Routing](https://docs.astro.build/en/guides/routing/). It is file-based, meaning you define routes through files in the `pages` folder. For example, if you add a page `hello.php` in the `pages` folder, you can access it at `http://localhost:8009/hello`. You can also define nested routes by creating a folder with the route name and adding an `index.php` file inside it. For example, `pages/about/index.php` is accessible at `http://localhost:8009/about`.

## Home Page

The `index.php` file in the `pages` folder is the home page. So `pages/index.php` is accessible at `http://localhost:8009`. This also works for nested routes, such as `pages/about/index.php` for `http://localhost:8009/about`.

## HTTP Methods and Verbs

Darken supports HTTP method-specific routing through filename conventions. You can specify which HTTP methods a route should respond to by using the method name in the filename before the `.php` extension.

### Single HTTP Method

To create a route that only responds to specific HTTP methods, use the method name in the filename:

- `contact.get.php` - Only responds to GET requests
- `contact.post.php` - Only responds to POST requests  
- `user.put.php` - Only responds to PUT requests
- `user.delete.php` - Only responds to DELETE requests
- `data.patch.php` - Only responds to PATCH requests

### Multiple HTTP Methods

You can make a single file respond to multiple HTTP methods by separating them with a pipe (`|`):

- `api.get|post.php` - Responds to both GET and POST requests
- `user.put|patch|delete.php` - Responds to PUT, PATCH, and DELETE requests

### Default Behavior

If no HTTP method is specified in the filename (e.g., `contact.php`), the route will respond to all HTTP methods (`*`).

## Route Parameters

The routing system supports advanced route parameters with type constraints, declared with `[[` and `]]` in the file name. Parameters can be simple or include type prefixes for validation.

### Basic Parameters

```php
// File: pages/hello-[[name]].php
// URL: /hello-world (name = "world")
<?php
$page = new class {
    #[\Darken\Attributes\RouteParam]
    public string $name;

    public function getGreeting(): string
    {
        return 'Hello, ' . ucfirst($this->name) . '!';
    }
};
?>
<h1><?= $page->getGreeting(); ?></h1>
```

### Typed Parameters

Darken supports several parameter type prefixes that automatically validate the parameter format:

#### Digit Parameters (`d:`)
```php
// File: pages/user-[[d:id]].php
// URL: /user-123 (id = "123")
// Only matches numeric values
```

#### Word Parameters (`w:`)
```php
// File: pages/category-[[w:slug]].php  
// URL: /category-electronics (slug = "electronics")
// Only matches alphanumeric characters
```

#### Slug Parameters (`s:`)
```php
// File: pages/blog-[[s:slug]].php
// URL: /blog-my-awesome-post (slug = "my-awesome-post")  
// Matches alphanumeric characters and hyphens
```

#### Alpha Parameters (`a:`)
```php
// File: pages/tag-[[a:name]].php
// URL: /tag-javascript (name = "javascript")
// Only matches alphabetic characters
```

#### Hexadecimal Parameters (`h:`)
```php
// File: pages/color-[[h:hex]].php
// URL: /color-ff0000 (hex = "ff0000")
// Only matches hexadecimal characters (0-9, a-f, A-F)
```

### Parameter Validation Patterns

Each type prefix corresponds to a specific regex pattern:

- `d:` → `[0-9]+` (digits only)
- `w:` → `[a-zA-Z0-9]+` (word characters)
- `s:` → `[a-zA-Z0-9-]+` (slug format with hyphens)
- `a:` → `[a-zA-Z]+` (alphabetic only)
- `h:` → `[a-fA-F0-9]+` (hexadecimal)
- Default → `[a-zA-Z0-9-]+` (slug format)

### Complex Parameter Examples

```php
// File: pages/api/user-[[d:id]]/posts-[[s:slug]].get.php
// URL: /api/user-123/posts-my-first-post
// Only responds to GET requests, validates ID as numeric and slug format

// File: pages/product-[[w:category]]-[[d:id]].get|post.php  
// URL: /product-electronics-456
// Responds to GET and POST, validates category as word and ID as numeric
```

## Wildcards and Catch-All Routes

You can catch all routes inside a folder (or the root) unless there is an exact match page available by using `...` (three dots) in the route parameter.

### Basic Wildcards

```php
// File: pages/[[...slug]].php
// Matches: /any/path/structure
// The slug parameter will contain the entire path
```

### Nested Wildcards

```php
// File: pages/docs/[[...path]].php  
// Matches: /docs/getting-started/installation
// The path parameter will contain "getting-started/installation"
```

### Wildcard with HTTP Methods

```php
// File: pages/api/[[...endpoint]].get.php
// Only matches GET requests to any path under /api/
```

## Route Priority and Matching

Routes are matched in the following priority order:

1. **Exact matches** - Static files without parameters
2. **Parameterized routes** - Files with `[[param]]` syntax
3. **Wildcard routes** - Files with `[[...param]]` syntax

### Example Priority

```
pages/
├── about.php              # Priority 1: /about
├── about-[[slug]].php      # Priority 2: /about-something  
└── [[...catch]].php        # Priority 3: /about/anything/else
```

## Advanced Routing Examples

### API Endpoints with Type Safety

```php
// File: pages/api/users/[[d:id]].get.php
<?php
$page = new class {
    #[\Darken\Attributes\RouteParam]
    public string $id;
    
    public function getUser(): array
    {
        // $id is guaranteed to be numeric due to 'd:' prefix
        return ['user' => 'User ' . $this->id];
    }
};

header('Content-Type: application/json');
echo json_encode($page->getUser());
```

### Resource Routes with Multiple Methods

```php
// File: pages/api/posts/[[d:id]].get|put|delete.php
<?php
$page = new class {
    #[\Darken\Attributes\RouteParam]
    public string $id;
    
    public function handleRequest(): array
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        return match($method) {
            'GET' => $this->getPost(),
            'PUT' => $this->updatePost(), 
            'DELETE' => $this->deletePost(),
            default => ['error' => 'Method not allowed']
        };
    }
    
    private function getPost(): array
    {
        return ['post' => 'Post ' . $this->id];
    }
    
    private function updatePost(): array
    {
        return ['message' => 'Post ' . $this->id . ' updated'];
    }
    
    private function deletePost(): array
    {
        return ['message' => 'Post ' . $this->id . ' deleted'];
    }
};

header('Content-Type: application/json');
echo json_encode($page->handleRequest());
```

### Complex Nested Routes

```php
// File: pages/shop/[[w:category]]/[[s:product]]/[[d:variant]].php
// URL: /shop/electronics/smartphone-pro/123
<?php
$page = new class {
    #[\Darken\Attributes\RouteParam]
    public string $category;
    
    #[\Darken\Attributes\RouteParam]  
    public string $product;
    
    #[\Darken\Attributes\RouteParam]
    public string $variant;
    
    public function getProductInfo(): string
    {
        return "Category: {$this->category}, Product: {$this->product}, Variant: {$this->variant}";
    }
};
?>
<h1><?= $page->getProductInfo(); ?></h1>
```
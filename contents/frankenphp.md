---
title: FrankenPHP
description: Run PHP applications in a serverless environment with FrankenPHP.
---

# FrankenPHP

You can run your Darken application in a serverless environment using FrankenPHP. To do this, update your `index.php` to handle FrankenPHP requests:

```php
<?php
declare(strict_types=1);

use App\Config;
use Darken\Web\Application;

// Prevent worker script termination when a client connection is interrupted
ignore_user_abort(true);

// Boot your app
include __DIR__ . '/../vendor/autoload.php';

$config = new Config(
    rootDirectoryPath: dirname(__DIR__),
);

$app = new Application($config);

// Handler outside the loop for better performance (doing less work)
$handler = static function () use ($app) {
   $app->run();
};

$maxRequests = (int)($_SERVER['MAX_REQUESTS'] ?? 0);
for ($nbRequests = 0; !$maxRequests || $nbRequests < $maxRequests; ++$nbRequests) {
    $keepRunning = \frankenphp_handle_request($handler);
    // Call the garbage collector to reduce the chances of it being triggered in the middle of a page generation
    gc_collect_cycles();
    if (!$keepRunning) {
        break;
    }
}
```

To start FrankenPHP with Docker:

```bash
docker run \
    -e FRANKENPHP_CONFIG="worker ./public/index.php" \
    -e SERVER_NAME=":8081 https:8082" \
    -v $PWD:/app \
    -p 8081:8081 -p 8082:8082 \
    dunglas/frankenphp
```
---
title: Extensions
description: Extend Darken PHP with custom extensions.
---

# Building Extensions

Each build run will expose the `Extension.php` to the `.build` directory, available under the namespace `Vendor\Extension\Build` (based on the config used in the `darken` bin file of your project root). The extension implements the @(Darken\Service\Extension) class.

If your extension uses and registers a container, this container will be a required parameter in the `Extension.php` constructor. This ensures the app loading your extension has all available containers to inject.

## Darken First-Party Extensions

See [Darken PHP Extensions](https://github.com/darkenphp-extensions) for a list of first-party extensions.
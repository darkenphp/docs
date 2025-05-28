---
title: Extensions
description: Extend Darken PHP with custom extensions.
---

# Building Extensions

Each build run will expose the `Extension.php` to the `.build` directory, which is available under the namespace `Vendor\Extension\Build` (based on the Config used in tune `darken` bin file of your Project Root), the Extension implements @(Darken\Service\Extension) class.

If your extension useses and registers a Container, this Container will be a required Parameter in the `Extension.php` constructor. This ensures the app which loads your extension has all available Containers to inject.

## Darken First Party Extensions

See [https://github.com/darkenphp-extensions[Darken PHP Extensions]] for a list of first party extensions.
---
title: Config
description: Object oriented configuration in Darken PHP.
---

# Configuration

One of the main goals behind the Framework is to have everything as PHP objects available, this might look complicated at first sight but it has a lot of advantages. The configuration is no exception to this rule, it is also an object that can be extended and modified. Which means your IDE can help you with autocompletion and you can use the full power of PHP to create your configuration.

## Configuration Object

Every configuration must implement @(Darken\Config\ConfigInterface)

## Base Config for good Starting Point

@(Darken\Config\BaseConfig) is a good starting point for your configuration, it already implements the `ConfigInterface` and has some useful methods.

## Environment Variables

## Add methods to your Config
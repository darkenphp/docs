---
title: Installation
description: Get started with Darken PHP.
---

# Getting Started

To create a new project, run the following command in your terminal. Replace `myapp` with your desired folder name:

```bash
composer create-project darkenphp/app myapp "dev-main"
```

After the folder is created, navigate to the project directory and launch the development server using:

```bash
composer dev
```

Once the server is running, open your browser and go to:

```
http://localhost:8009
```

The development server supports hot reloading, automatically compiling your changes as you modify the source files. Be sure to review the `composer.json` file and check out the scripts section for additional configuration options.
---
title: Tailwind
description: Get started with Tailwind CSS.
---

# Tailwind

[Tailwind CSS](https://tailwindcss.com/) is a utility-first CSS framework for rapidly building custom designs. It is a low-level framework that provides a set of utility classes that can be used to build custom designs. It is a great tool for building custom designs without writing custom CSS and is perfectly designed for [component based development](/components) approach. This guide will show you how to get started with Tailwind CSS in Darken. For setting up Tailwind, Node is required.

## Setup

Install the Tailwind CSS package using npm

```bash
npm install -D tailwindcss
```

Create a Tailwind configuration file, this can be done by running the following command and will create a `tailwind.config.js` file in the root of the project:

```bash
npx tailwindcss init
```

Adjust the configuration file to include the files that Tailwind should scan for classes. This can be done by adding the following to the `tailwind.config.js` file:

```js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/**/*.{html,js,php}",
    "./pages/**/*.{html,js,php}",
    "./components/**/*.{html,js,php}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

Now create the CSS file that will be used to include the Tailwind classes. This can be done by creating a CSS file somewhere in the project, for example `resources/css/tailwind.css` , the file should include the following:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

.your-custom-clas {
    @apply bg-red-500;
}
```

Now add the Tailwind CSS build script to the `package.json` file and run the build script to start the Tailwind CSS compiler. The build script should look like the following:

```json
{
  "devDependencies": {
    "tailwindcss": "^3.4.17"
  },
  "scripts": {
    "build": "npx tailwindcss -i ./resources/css/tailwind.css -o ./public/assets/output.css --watch"
  }
}
```

Ensure to create the output directory `public/assets` and run `npm run build` to start the compiler then include the output.css in the layout file:

```html
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/output.css" rel="stylesheet">
</head>
```
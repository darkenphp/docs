# Tailwind

## 1. Install Tailwind 
npm install -D tailwindcss

## 2. Create a Tailwind configuration file
npx tailwindcss init

## 3. Create a Config file

create a tailwind.config.js file in the root of the project

```js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/**/*.{html,js,php}",
    "./components/**/*.{html,js,php}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

## 4. Create the CSS file
create css file somwhere, we used `resources/css/tailwind.css`

## 5. Start

Start the tailwind compiler and create a package.json entry for it 

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

## 6. Include the CSS file in the layout

Ensure to create the output directory `public/assets` and run `npm run build` to start the compiler then include the output.css in the layout file

```html
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./assets/output.css" rel="stylesheet">
</head>
```
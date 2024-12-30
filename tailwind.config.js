/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/**/*.{html,js,php}",
    "./components/**/*.{html,js,php}",
  ],
  theme: {
    colors: {
      darken: '#121212',
      primary: '#ff2c2c',
      grey: 'grey',
      lightgrey: '#f5f5f5',
      darkgrey: '#1b1b1b',
      white: 'white',
      black: 'black',
    },
    extend: {},
  },
  plugins: [],
}


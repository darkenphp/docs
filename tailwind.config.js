/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/**/*.{html,js,php}",
    "./components/**/*.{html,js,php}",
  ],
  theme: {
    colors: {
      darken: '#121212',
      orange: '#FF6F00',
      softorange: '#FFA726',
      deeporange: '#E65100',
      grey: 'grey',
      lightgrey: '#757575',
      darkgrey: '#424242',
      white: 'white',
      black: 'black',
    },
    extend: {},
  },
  plugins: [],
}


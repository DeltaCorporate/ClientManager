module.exports = {
  purge: [

    './resources/**/*.html.twig',

    './resources/**/*.js',

    './resources/**/*.vue',
  ],
  darkMode: 'class', // or 'media' or 'class'
  theme: {
    extend: {
      colors:{
        "primary":{
          "100":"#e5a32b"
        }
      }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}

module.exports = {
  content: [
    './ressources/views/**/*.twig',
  ],
  theme: {
    extend: {
      colors:{
        "primary": {
          "50": "#009688",
          "100": "#060818",
          "200": "#0E1726",
          "300": "#1A1C2D",
          "400": "#506690",
          "500": "#888EA8",
        }
      }
    },
  },
  plugins: [
    require("tailwind-scrollbar-hide")
  ],
}

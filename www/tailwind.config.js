module.exports = {
    purge: [

        './resources/**/*.html.twig',

        './resources/**/*.js',

        './resources/**/*.vue',
    ],
    darkMode: 'class', // or 'media' or 'class'
    theme: {
        extend: {
            colors: {
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
    variants: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'class',
        }),
    ],
}

const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            padding: {
                "1/4": "25%",
                full: "100%",
            },
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                xtiny: ".50rem",
                tiny: ".65rem",
            },
            colors: {
                red: "#E63946",
                black: "#000000",
                darkblue: "#1D3557",
                lightblue: "#103BA8",
                dark: "#222222",
                textdark: "#000000",
                white: "#FFFFFF",
                lightgrey: "#B5B5B6",
            },
            backgroundImage: {
                "top-1": "url('/images/bg-1.jpg')",
                laptop: "url('/images/laptop-bg.png')",
                "mobile-shock": "url('/images/mobile-shock-bg.png')",
            },
            animation: {
                fadeout: "fadeOut 1s ease-in-out",
                fadein: "fadeIn 1s ease-in-out",
                wiggle: "wiggle 1s ease-in-out infinite",
            },

            // that is actual animation
            keyframes: (theme) => ({
                fadeOut: {
                    "0%": { backgroundColor: theme("colors.red.300") },
                    "100%": { backgroundColor: theme("colors.transparent") },
                },
                fadeIn: {
                    "100%": { backgroundColor: theme("colors.transparent") },
                    "0%": { backgroundColor: theme("colors.red.300") },
                },

                wiggle: {
                    "0%, 100%": { transform: "rotate(-3deg)" },
                    "50%": { transform: "rotate(3deg)" },
                },
            }),
        },
        variants: {
            extend: {
                animation: ["hover", "group-hover"],
            },
        },
        screens: {
            xs: "360px",
            ...defaultTheme.screens,
        },
        // theme: {

        //   }
    },

    plugins: [require("@tailwindcss/forms"), require("flowbite/plugin")],
};

import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "media",

    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                tajawal: ["Tajawal", "sans-serif"],
            },
            colors: {
                "gymz-dark": "#0f172a", // Deep slate background
                "gymz-glass": "rgba(255, 255, 255, 0.05)", // Liquid Glass base
                "gymz-accent": "#10b981", // Emerald Green for interaction/highlights
            },
            backdropBlur: {
                xs: "2px",
                sm: "4px",
                md: "8px",
                lg: "12px",
            },
            boxShadow: {
                glass: "0 4px 30px rgba(0, 0, 0, 0.1)",
                "glass-inset": "inset 0 0 0 1px rgba(255, 255, 255, 0.1)",
            },
        },
    },

    plugins: [forms],
};

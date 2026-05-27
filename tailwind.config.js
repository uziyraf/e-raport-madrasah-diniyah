import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['"Liberation Serif"', 'Georgia', '"Times New Roman"', 'serif'],
                arabic: ['"Noto Naskh Arabic"', '"Amiri"', 'serif'],
            },
            colors: {
                brand: {
                    primary: '#042f2e',
                    active: '#064e3b',
                    accent: '#fdba74',
                },
            },
        },
    },

    plugins: [forms],
};

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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#f0f4ff',
                    100: '#e0eaff',
                    500: '#1D3557',
                    600: '#162840',
                    700: '#0f1d2e',
                },
            },
        },
    },

    plugins: [forms],
};

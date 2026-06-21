import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                indigo: {
                    50: '#f4f6fc',
                    100: '#e2e8f5',
                    200: '#cbd5f0',
                    300: '#9bb8ff',
                    400: '#7487d6',
                    500: '#465cbd',
                    600: '#1f2ba5', // Logo Blue
                    700: '#171e87',
                    800: '#171c6d',
                    900: '#171b5a',
                    950: '#0e0f37',
                },
                slate: {
                    50: '#f4f6fc',
                    100: '#e2e8f5',
                    200: '#cbd5f0',
                    300: '#9bb8ff',
                    400: '#7487d6',
                    500: '#465cbd',
                    600: '#1f2ba5',
                    700: '#171e87',
                    800: '#171c6d',
                    900: '#171b5a',
                    950: '#0e0f37',
                },
                emerald: {
                    50: '#f4f6fc',
                    100: '#cbd5f0',
                    200: '#9bb8ff',
                    500: '#465cbd',
                    600: '#1f2ba5',
                },
                rose: {
                    50: '#f4f6fc',
                    100: '#cbd5f0',
                    200: '#9bb8ff',
                    500: '#465cbd',
                    600: '#1f2ba5',
                },
                amber: {
                    50: '#f4f6fc',
                    100: '#cbd5f0',
                    200: '#9bb8ff',
                    500: '#465cbd',
                    600: '#1f2ba5',
                }
            },
            fontFamily: {
                sans: ['Inter', 'Outfit', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                'soft': '0 1px 2px 0 rgb(0 0 0 / 0.04)',
                'card': '0 1px 3px 0 rgb(0 0 0 / 0.06), 0 1px 2px -1px rgb(0 0 0 / 0.06)',
                'card-hover': '0 4px 6px -1px rgb(0 0 0 / 0.07), 0 2px 4px -2px rgb(0 0 0 / 0.05)',
                'elevated': '0 10px 15px -3px rgb(0 0 0 / 0.06), 0 4px 6px -4px rgb(0 0 0 / 0.06)',
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
        },
    },

    plugins: [forms],
};

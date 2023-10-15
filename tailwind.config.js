import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
const colors = require('tailwindcss/colors') 

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/awcodes/filament-tiptap-editor/resources/**/*.blade.php', 
    ],

    theme: {
        extend: {
            backgroundImage: {
                'header-wave': "url('/img/wave.svg')",
                'footer-wave': "url('/img/wave.svg')",
              },
            fontFamily: {
                sans: ['Open Sans', 'sans-serif'],
            },
            colors: {
                'primary-red': '#A71930',
                'primary-blue': '#002664',
                'link': '#0096FF',
                danger: {
                    50: '#800000',
                    100: '#800000',
                    200: '#800000',
                    300: '#800000',
                    400: '#800000',
                    500: '#800000',
                    600: '#800000',
                    700: '#800000',
                    800: '#800000',
                    900: '#800000',
                },
                //primary: colors.blue,
                primary: {
                    50: '#A71930',
                    100: '#A71930',
                    200: '#A71930',
                    300: '#A71930',
                    400: '#A71930',
                    500: '#A71930',
                    600: '#A71930',
                    700: '#A71930',
                    800: '#A71930',
                    900: '#A71930',
                },
                success: colors.green,
                warning: colors.yellow,
            },
            animation: {
                'spin-slow': 'spin 2s linear infinite',
            }
        },
    },

    plugins: [forms],
};

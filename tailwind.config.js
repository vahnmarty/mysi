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
    ],

    theme: {
        extend: {
            backgroundImage: {
                'header-wave': "url('/img/wave.svg')",
                'footer-wave': "url('/img/wave.svg')",
              },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary-red': '#A71930',
                'primary-blue': '#002664',
                'link': '#0096FF',
                danger: colors.rose,
                primary: colors.blue,
                success: colors.green,
                warning: colors.yellow,
            }
        },
    },

    plugins: [forms],
};

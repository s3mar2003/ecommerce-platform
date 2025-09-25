import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    900: '#0c4a6e',
                },
                secondary: {
                    50: '#faf6f0',
                    100: '#f4ebdc',
                    500: '#d97706',
                    600: '#b45309',
                }
            },
            // إضافة دعم للغة العربية مع اتجاه يساري
            textAlign: {
                'right': 'right',
                'left': 'left',
            },
            // تحسينات للغة العربية مع LTR
            spacing: {
                'rtl-safe': '1rem', // مسافات آمنة للعربية
            }
        },
    },
    plugins: [],
}
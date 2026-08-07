import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './Modules/**/resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#eff8ff',
                    100: '#dbefff',
                    200: '#b8dfff',
                    300: '#85c9ff',
                    400: '#4aabff',
                    500: '#2188f3',
                    600: '#096bd1',
                    700: '#0855aa',
                    800: '#0b478a',
                    900: '#0f3d72',
                    950: '#082646',
                },
                ink: {
                    50: '#f5f8fa',
                    100: '#e8eef2',
                    200: '#cedbe2',
                    300: '#a8bdc8',
                    400: '#7896a5',
                    500: '#587987',
                    600: '#45616e',
                    700: '#394f5a',
                    800: '#243943',
                    900: '#142832',
                    950: '#081a23',
                },
                gold: {
                    50: '#fffbea',
                    100: '#fff3c5',
                    200: '#ffe787',
                    300: '#ffd649',
                    400: '#fbc019',
                    500: '#dda006',
                    600: '#b87802',
                    700: '#925706',
                    800: '#78450b',
                    900: '#66390f',
                },
                canvas: '#f4f7fb',
            },
            boxShadow: {
                soft: '0 20px 50px -30px rgba(15, 35, 48, 0.30)',
                card: '0 24px 60px -36px rgba(8, 38, 70, 0.35)',
                glow: '0 20px 50px -20px rgba(33, 136, 243, 0.45)',
            },
            animation: {
                float: 'float 7s ease-in-out infinite',
                floatSlow: 'floatSlow 10s ease-in-out infinite',
                pulseSoft: 'pulseSoft 4s ease-in-out infinite',
                fadeUp: 'fadeUp .8s cubic-bezier(.22,1,.36,1) both',
                fadeIn: 'fadeIn .7s ease-out both',
                shimmer: 'shimmer 2.4s linear infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translate3d(0, 0, 0)' },
                    '50%': { transform: 'translate3d(0, -14px, 0)' },
                },
                floatSlow: {
                    '0%, 100%': { transform: 'translate3d(0, 0, 0) scale(1)' },
                    '50%': { transform: 'translate3d(18px, -18px, 0) scale(1.05)' },
                },
                pulseSoft: {
                    '0%, 100%': { opacity: '.35', transform: 'scale(1)' },
                    '50%': { opacity: '.65', transform: 'scale(1.08)' },
                },
                fadeUp: {
                    '0%': { opacity: '0', transform: 'translateY(24px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-700px 0' },
                    '100%': { backgroundPosition: '700px 0' },
                },
            },
        },
    },

    plugins: [forms],
};

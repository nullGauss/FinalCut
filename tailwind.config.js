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
            colors: {
                // FinalCut Design Tokens
                ink: '#111111',
                'ink-secondary': '#444444',
                background: '#F7F7F7',
                surface: '#FFFFFF',
                'blue-bg': '#DCE9F5',
                'blue-text': '#1F4E79',
                'yellow-bg': '#FBEBA0',
                'yellow-text': '#6B5900',
            },
            fontFamily: {
                display: ['Space Grotesk', ...defaultTheme.fontFamily.sans],
                body: ['Inter', ...defaultTheme.fontFamily.sans],
                handwriting: ['Indie Flower', 'cursive'],
            },
            boxShadow: {
                'hard': '3px 3px 0 #111111',
                'hard-sm': '2px 2px 0 #111111',
                'hard-lg': '4px 4px 0 #111111',
            },
            borderWidth: {
                '1.5': '1.5px',
            },
            borderColor: {
                ink: '#111111',
            },
        },
    },

    plugins: [forms],
};
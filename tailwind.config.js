const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'tb-primary': '#78c1c5',
            },
            keyframes: {
                'typing-dot': {
                    '0%, 100%': {
                        background: 'rgba(150, 150, 150, 0.4)'
                    }
                },
                'typing-dot-1': {
                    '33.333%': {
                        background: '#969696'
                    }
                },
                'typing-dot-2': {
                    '66.6667%': {
                        background: '#969696'
                    }
                },
                'typing-dot-3': {
                    '100%': {
                        background: '#969696'
                    }
                },
            },
            animation: {
                'typing-dot-1': 'typing-dot-1 1s ease-in-out infinite',
                'typing-dot-2': 'typing-dot-2 1s ease-in-out infinite',
                'typing-dot-3': 'typing-dot-3 1s ease-in-out infinite',
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};

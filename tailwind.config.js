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
            },
            colors: {
                primary: {
                    DEFAULT: 'var(--color-primary, #1E3A8A)',
                    50: 'var(--color-primary-50, #EFF6FF)',
                    100: 'var(--color-primary-100, #DBEAFE)',
                    200: 'var(--color-primary-200, #BFDBFE)',
                    300: 'var(--color-primary-300, #93C5FD)',
                    400: 'var(--color-primary-400, #60A5FA)',
                    500: 'var(--color-primary-500, #3B82F6)',
                    600: 'var(--color-primary-600, #2563EB)',
                    700: 'var(--color-primary-700, #1D4ED8)',
                    800: 'var(--color-primary, #1E3A8A)',
                    900: 'var(--color-primary-900, #1E3A8A)',
                },
                accent: {
                    DEFAULT: 'var(--color-accent, #6366F1)',
                    100: 'var(--color-accent-100, #E0E7FF)',
                    500: 'var(--color-accent, #6366F1)',
                    600: 'var(--color-accent-600, #4F46E5)',
                },
                positive: {
                    DEFAULT: 'var(--color-positive, #16A34A)',
                    50: 'var(--color-positive-50, #F0FDF4)',
                    100: 'var(--color-positive-100, #DCFCE7)',
                    200: 'var(--color-positive-200, #BBF7D0)',
                    300: 'var(--color-positive-300, #86EFAC)',
                    400: 'var(--color-positive-400, #4ADE80)',
                    500: 'var(--color-positive-500, #22C55E)',
                    600: 'var(--color-positive, #16A34A)',
                    700: 'var(--color-positive-700, #15803D)',
                    800: 'var(--color-positive-800, #166534)',
                    900: 'var(--color-positive-900, #14532D)',
                },
                negative: {
                    DEFAULT: 'var(--color-negative, #DC2626)',
                    50: 'var(--color-negative-50, #FEF2F2)',
                    100: 'var(--color-negative-100, #FEE2E2)',
                    200: 'var(--color-negative-200, #FECACA)',
                    300: 'var(--color-negative-300, #FCA5A5)',
                    400: 'var(--color-negative-400, #F87171)',
                    500: 'var(--color-negative-500, #EF4444)',
                    600: 'var(--color-negative, #DC2626)',
                    700: 'var(--color-negative-700, #B91C1C)',
                    800: 'var(--color-negative-800, #991B1B)',
                    900: 'var(--color-negative-900, #7F1D1D)',
                },
                neutral: {
                    DEFAULT: 'var(--color-neutral, #CA8A04)',
                    50: 'var(--color-neutral-50, #FEFCE8)',
                    100: 'var(--color-neutral-100, #FEF9C3)',
                    200: 'var(--color-neutral-200, #FEF08A)',
                    300: 'var(--color-neutral-300, #FDE047)',
                    400: 'var(--color-neutral-400, #FACC15)',
                    500: 'var(--color-neutral-500, #EAB308)',
                    600: 'var(--color-neutral, #CA8A04)',
                    700: 'var(--color-neutral-700, #A16207)',
                    800: 'var(--color-neutral-800, #854D0E)',
                    900: 'var(--color-neutral-900, #713F12)',
                },
                bg: 'var(--color-bg, #F8FAFC)',
            }
        },
    },

    plugins: [forms],
};

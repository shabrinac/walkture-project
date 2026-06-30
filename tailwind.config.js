/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["'Inter'", 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
                sage: {
                    50:  '#f0f5f1',
                    100: '#dceadd',
                    200: '#b9d4bb',
                    300: '#8fb894',
                    400: '#7ba082',
                    500: '#5c7d62',
                    600: '#486350',
                    700: '#384e3f',
                    800: '#2b3d31',
                    900: '#1d2b23',
                },
                neutral: {
                    50:  '#f9fafb',
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#6b7280',
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                },
            },
            boxShadow: {
                'card': '0 1px 3px 0 rgba(0,0,0,0.06), 0 1px 2px -1px rgba(0,0,0,0.06)',
                'card-hover': '0 4px 16px -2px rgba(0,0,0,0.10), 0 2px 6px -2px rgba(0,0,0,0.06)',
                'sidebar': '2px 0 16px -4px rgba(0,0,0,0.08)',
            },
            borderRadius: {
                'xl':  '0.875rem',
                '2xl': '1.25rem',
            },
            transitionTimingFunction: {
                'smooth': 'cubic-bezier(0.4, 0, 0.2, 1)',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};

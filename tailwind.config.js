module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './src/**/*.php',
    ],
    safelist: [
        'invisible',
        'h-0',
        'overflow-hidden',
        'p-0',
        'p-6',
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};

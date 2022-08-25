// const { colors } = require('laravel-mix/src/Log');
const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: 'jit',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        transparent: 'transparent',
        current: 'currentColor',
        black: colors.black,
        'white': colors.white,
        'gray': colors.neutral,
        'gray-background': '#f7f8fc',
        'blue': '#328af1',
        'blue-hover': '#2879bd',
        'yellow': '#f9c851',
        'red': '#ec454f',
        'green': '#1aab8b',
        'purple': '#8b60ed',
      },
      spacing: {
        22: '5.5rem',
        44: '11rem',
        70: '17.5rem',
        76: '19rem',
        104: '26rem',
        175: '43.75rem',
      },
      maxWidth: {
        custom: '68.5rem',
      },
      boxShadow: {
        card: '4px 4px 15px rgba(36, 37, 38, 0.08)',
        dialog: '3px 4px 15px rgba(36, 37, 38, 0.22)',
      },
      fontFamily: {
        sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
      },
      fontSize: {
        xxs: ['0.625rem', { lineHeight: '1rem' }],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/line-clamp'),
  ],
};

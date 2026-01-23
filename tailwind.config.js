const colors = require('tailwindcss/colors')

/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.jsx',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        // Custom brand colors
        meepleYellow: '#FFD700',
        meepleRed: '#F04C4C',
        meepleBlue: '#1E90FF',

        // Extended Tailwind colors for refined gradients
        rose: colors.rose,
        pink: colors.pink,
        fuchsia: colors.fuchsia,
        violet: colors.violet,
        purple: colors.purple,
        indigo: colors.indigo,
        blue: colors.blue,
        sky: colors.sky,
        cyan: colors.cyan,
        teal: colors.teal,
        emerald: colors.emerald,
        green: colors.green,
        lime: colors.lime,
        yellow: colors.yellow,
        amber: colors.amber,
        orange: colors.orange,
        red: colors.red,
        slate: colors.slate,
        gray: colors.gray,
        zinc: colors.zinc,
        neutral: colors.neutral,
        stone: colors.stone,
      }
    },
  },
  plugins: [],
}

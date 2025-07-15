/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class', // ✅ Add this line
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.jsx',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
      meepleYellow: '#FFD700',
      meepleRed: '#F04C4C',
      meepleBlue: '#1E90FF',
    }
    },
  },
  plugins: [],
}

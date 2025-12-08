/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          dark: '#19203B',
          medium: '#263053',
        },
        secondary: {
          yellow: '#FBC143',
        },
        background: {
          light: '#ECF1F9',
        }
      }
    },
  },
  plugins: [],
}
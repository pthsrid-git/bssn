const modulePath = ['./node_modules/@bssn/**/*.{vue,js,ts,jsx,tsx}']

/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}', ...modulePath],
  important: true,
  safelist: [
    'max-w-xs',
    'max-w-sm',
    'max-w-md',
    'max-w-lg',
    'max-w-xl',
    'max-w-2xl',
    'max-w-3xl',
    'max-w-4xl',
    'max-w-5xl',
    'max-w-6xl',
    'max-w-7xl'
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#E5E8F4',
          100: '#CCD1E9',
          200: '#99A4D5',
          300: '#6679BF',
          400: '#45548C',
          500: '#263053',
          600: '#202847',
          700: '#171E37',
          800: '#11172B',
          900: '#090D1D',
          950: '#040712'
        },
        info: {
          50: '#EEF0FE',
          100: '#D9DEFD',
          200: '#B2BEFB',
          300: '#8DA1F9',
          400: '#5F81F6',
          500: '#2563EB',
          600: '#1C4FBE',
          700: '#133C94',
          800: '#0A2767',
          900: '#041642',
          950: '#020E30'
        },
        warning: {
          50: '#FEF8F1',
          100: '#FEF2E3',
          200: '#FDE8CD',
          300: '#FCDBAB',
          400: '#FCCD81',
          500: '#FBC143',
          600: '#DFAB38',
          700: '#C89931',
          800: '#AE8529',
          900: '#957122',
          950: '#89681E'
        },
        success: {
          50: '#D5FFD0',
          100: '#B0FEA4',
          200: '#5BF227',
          300: '#52DC23',
          400: '#48C41D',
          500: '#3FAE19',
          600: '#3AA317',
          700: '#369714',
          800: '#308911',
          900: '#2B7D0F',
          950: '#29780E'
        },
        danger: {
          50: '#FDEDED',
          100: '#FBDBDC',
          200: '#F8BABB',
          300: '#F59295',
          400: '#F36A6E',
          500: '#EB303B',
          600: '#E22E38',
          700: '#D92C36',
          800: '#D02933',
          900: '#C72730',
          950: '#C3262F'
        },
        neutral: {
          50: '#FCFCFD',
          100: '#F5F6F8',
          200: '#ECEEF1',
          300: '#E2E6EA',
          400: '#D8DDE4',
          500: '#D0D6DE',
          600: '#B5BFCC',
          700: '#99A7B9',
          800: '#838F9F',
          900: '#707B88',
          950: '#646E7A'
        }
      },
      fontSize: {
        '2xs': [
          '0.625rem',
          {
            lineHeight: '0.75rem'
          }
        ]
      },
      spacing: {
        18: '4.5rem'
      },
      height: {
        18: '4.5rem',
        34: '8.5rem'
      },
      animation: {
        'spin-slow': 'spin 3s cubic-bezier(0.4, 0, 0.6, 1) infinite'
      }
    }
  },
  plugins: []
}

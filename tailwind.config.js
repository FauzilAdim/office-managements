/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
      },
      colors: {
        brand: {
          50: '#EEF2FF',
          100: '#E0E7FF',
          200: '#C7D2FE',
          300: '#A5B4FC',
          400: '#818CF8',
          500: '#6366F1', // Primary accent
          600: '#4F46E5', // Hover
          700: '#4338CA',
          800: '#3730A3',
          900: '#312E81',
          950: '#1E1B4B',
        },
        surface: {
          light: '#FAFAFA',
          dark: '#09090B',
          card: '#FFFFFF',
          cardDark: '#121215',
          sidebar: '#0C0C0E',
        },
        border: {
          light: '#E4E4E7',
          dark: '#27272A',
        },
        content: {
          heading: '#18181B',
          muted: '#71717A',
          subtle: '#A1A1AA',
        }
      },
      borderRadius: {
        '2xl': '1rem',
        '3xl': '1.25rem',
      },
      boxShadow: {
        'sm': '0 1px 2px 0 rgba(0, 0, 0, 0.04)',
        'md': '0 4px 12px 0 rgba(0, 0, 0, 0.05)',
      }
    },
  },
  plugins: [],
}

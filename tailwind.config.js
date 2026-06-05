/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./landing.html"],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Yekan Bakh FaNum', 'sans-serif'],
      },
      colors: {
        // پرچم ایران: سبز
        primary: {
          DEFAULT: '#16A34A', // Green 600
          dark: '#15803D',    // Green 700
          light: '#DCFCE7',   // Green 100
        },
        // پرچم ایران: قرمز
        secondary: {
          DEFAULT: '#DC2626', // Red 600
          dark: '#B91C1C',    // Red 700
          light: '#FEE2E2',   // Red 100
        },
        // پرچم ایران: طلایی/زعفرانی
        accent: {
          DEFAULT: '#EAB308', // Yellow 500
          dark: '#CA8A04',    // Yellow 600
          light: '#FEF9C3',   // Yellow 100
        },
        light: '#F8FAFC',
      },
      boxShadow: {
        'soft': '0 10px 40px -10px rgba(0,0,0,0.08)',
        'glow': '0 0 20px rgba(22, 163, 74, 0.3)', // درخشش سبز رنگ
      }
    },
  },
  plugins: [],
}

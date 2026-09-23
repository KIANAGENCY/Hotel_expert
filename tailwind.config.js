/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './includes/**/*.php',
    './cuenta/**/*.php',
    './assets/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        expert: '#0B2345',
        turquesa: '#008C95',
        aqua: '#52C8C8',
        hielo: '#EAF5F5',
        arena: '#F3F0EA',
        charcoal: '#222326',
        eco: '#4D7C4D',
      },
      fontFamily: {
        heading: ['Montserrat', 'sans-serif'],
        body: ['"Source Sans 3"', 'sans-serif'],
      },
      boxShadow: {
        glass: '0 8px 40px rgba(11, 35, 69, 0.12)',
        lift: '0 24px 60px rgba(11, 35, 69, 0.18)',
      },
      backgroundImage: {
        brand: 'linear-gradient(135deg, #0B2345 0%, #008C95 100%)',
      },
    },
  },
  plugins: [],
};

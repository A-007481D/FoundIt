/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: { DEFAULT: "#8a2be2", foreground: "#ffffff" },
        secondary: { DEFAULT: "#10b981", foreground: "#ffffff" },
        muted: { DEFAULT: "#f1f5f9", foreground: "#64748b" },
        mutedrecent: { DEFAULT: "#f1f5f9", foreground: "#64748b" },
        background: "#ffffff",
        foreground: "#0f172a",
        border: "#e2e8f0"
      }
    }
  },
  plugins: [],
}

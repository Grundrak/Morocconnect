/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{js,jsx,ts,tsx,vue}"],
  theme: {
    extend: {
      colors: {
        "gray-0-white": "#fff",
        "gray-20": "#e2e8f0",
        "gray-30": "#cbd5e1",
        "success-50": "#22c55e",
        "gray-5": "#f8fafc",
        "brand-60": "#4f46e5",
        "gray-60": "#475569",
        "gray-80": "#1e293b",
        "brand-5": "#eef2ff",
        "brand-30": "#a5b4fc",
        "transparent-white-32": "rgba(255, 255, 255, 0.32)",
        black: "#000",
      },
      spacing: {
        "spacing-spacing-sm": "12px",
      },
      fontFamily: {
        "text-sm-medium": "'Plus Jakarta Sans'",
      },
      borderRadius: {
        "104xl": "123px",
        "73xl-3": "92.3px",
        "57xl-9": "76.9px",
        "1215xl": "1234px",
        "9980xl": "9999px",
        "border-radius-radius-full": "9999px",
      },
    },
    fontSize: {
      sm: "14px",
      base: "16px",
      inherit: "inherit",
    },
    screens: {
      lg: {
        max: "1200px",
      },
      mq750: {
        raw: "screen and (max-width: 750px)",
      },
      mq450: {
        raw: "screen and (max-width: 450px)",
      },
    },
  },
  corePlugins: {
    preflight: false,
  },
};

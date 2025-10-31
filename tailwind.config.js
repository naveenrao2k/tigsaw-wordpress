/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./tigsaw.php",
    "./uninstall.php",
    "./assets/**/*.{php,js,jsx,html}",
    "./includes/**/*.{php,js,jsx,html}"
  ],
  safelist: [
    // Add common patterns and dynamic classes here
    {
      pattern: /^(bg|text|border|from|to|via|ring|shadow|rounded|p|px|py|pt|pb|pl|pr|m|mx|my|mt|mb|ml|mr|w|h|min-w|max-w|min-h|max-h|grid-cols|col-span|row-span|gap|flex|items|justify|content|self|font|leading|tracking|z|opacity|scale|rotate|translate|skew|duration|delay|ease|transition|overflow|object|align|place|inset|top|bottom|left|right|order|space|divide|list|outline|visible|invisible|sr|cursor|select|pointer|resize|appearance|fill|stroke|table|caption|border-(solid|dashed|dotted|double|none))(-[a-zA-Z0-9-]+)*$/,
    },
    // Add any specific classes you know are used dynamically
  ],
  theme: {
    extend: {
      colors: {
        primary: '#ff6600',
        'primary-dark': '#e65c00',
        'primary-light': '#ff8533',
      }
    }
  },
  plugins: [],
  // Enable preflight to match CDN JIT output
  corePlugins: {
    preflight: true,
  }
}

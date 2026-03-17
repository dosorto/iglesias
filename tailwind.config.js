import flowbite from "flowbite/plugin";

export default {
    darkMode: 'class',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],
    safelist: [
        // Colores de acento dinámicos para matrimonio (blue, pink, violet, teal)
        // Tailwind v3: usar variants[] en lugar de prefijo en el pattern
        {
            pattern: /^bg-(blue|pink|violet|teal|emerald|amber|sky|rose|red)-(50|100|200|300|400|500|600|700|800|900)$/,
            variants: ['hover', 'dark', 'dark:hover'],
        },
        {
            pattern: /^border-(blue|pink|violet|teal|emerald|amber|sky|rose|red)-(50|100|200|300|400|500|600|700|800|900)$/,
            variants: ['dark'],
        },
        {
            pattern: /^text-(blue|pink|violet|teal|emerald|amber|sky|rose|red)-(50|100|200|300|400|500|600|700|800|900)$/,
            variants: ['dark'],
        },
        {
            pattern: /^ring-(blue|pink|violet|teal|emerald|amber|sky|rose|red)-(50|100|200|300|400|500|600|700|800|900)$/,
            variants: ['focus'],
        },
        {
            pattern: /^from-(blue|pink|violet|teal|emerald|amber|sky|rose|red)-(50|100|200|300|400|500|600|700|800|900)$/,
            variants: ['dark'],
        },
    ],
    theme: {
        extend: {
            colors: {
                'purpura-sagrado': '#4A1A6B',
                'dorado-divino':   '#C9A84C',
                'azul-mariano':    '#1B3A6B',
                'blanco-liturgico':'#F8F4EC',
                'marfil-calido':   '#EDE8DC',
                'rojo-martir':     '#8B1A1A',
                'verde-esperanza': '#2D5A3D',
                'gris-piedra':     '#6B6560',
            },
        },
    },
    plugins: [
        flowbite,
    ],
};

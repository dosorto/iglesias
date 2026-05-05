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
                'purpura-sagrado': '#0D5C3A',
                'dorado-divino':   '#C8A547',
                'azul-mariano':    '#2A79B3',
                'blanco-liturgico':'#F4F5F2',
                'marfil-calido':   '#E9ECE5',
                'rojo-martir':     '#A13B3B',
                'verde-esperanza': '#0F6E46',
                'gris-piedra':     '#253238',
            },
        },
    },
    plugins: [
        flowbite,
    ],
};

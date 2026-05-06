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
            pattern: /^(bg|text|border|ring)-(purpura-sagrado|dorado-divino|azul-mariano|rojo-martir|verde-esperanza|llama-espiritu)$/,
        },
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
                'purpura-sagrado':  '#5B21B6',
                'dorado-divino':    '#C8A547',
                'azul-mariano':     '#1E3A8A',
                'blanco-liturgico': '#FFF8E1',
                'marfil-calido':    '#F5F0E8',
                'rojo-martir':      '#991B1B',
                'verde-esperanza':  '#166534',
                'gris-piedra':      '#1E293B',
                'llama-espiritu':   '#C2410C',
            },
        },
    },
    plugins: [
        flowbite,
    ],
};

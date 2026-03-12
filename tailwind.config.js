import flowbite from "flowbite/plugin";

export default {
    darkMode: 'class',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
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

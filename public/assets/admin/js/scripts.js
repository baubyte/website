//Cambio de Modo
//Seleccionamos el boton para cambiar el color
const colorModeBtn = document.getElementById('colorMode');
//Escuchamos el click del botón
colorModeBtn.addEventListener('click', changeColorMode)
//Función para cambiar el mode de color de la pagina
function changeColorMode(event) {
    const body = document.body;
    //Seleccionamos el icono del botón para cambiar el color
    const colorModeIcon = document.getElementById('colorModeIcon');
    // Toggle dark mode class
    body.classList.toggle('dark-mode');

    // Update icon
    if (body.classList.contains('dark-mode')) {
        colorModeIcon.className = 'fa fa-sun';
        localStorage.setItem('darkMode', 'enabled');
    } else {
        colorModeIcon.className = 'fa fa-moon';
        localStorage.setItem('darkMode', 'disabled');
    }
}
// Check saved preference on page load
document.addEventListener('DOMContentLoaded', function () {
    const darkMode = localStorage.getItem('darkMode');
    //Seleccionamos el icono del botón para cambiar el color
    const colorModeIcon = document.getElementById('colorModeIcon');
    // Default to dark mode if no preference is saved
    if (darkMode === 'disabled') {
        // User explicitly chose light mode
        colorModeIcon.className = 'fa fa-moon';
    } else {
        // Default dark mode (first visit or user chose dark)
        document.body.classList.add('dark-mode');
        colorModeIcon.className = 'fa fa-sun';
        // Set localStorage if it's the first visit
        if (!darkMode) {
            localStorage.setItem('darkMode', 'enabled');
        }
    }
});
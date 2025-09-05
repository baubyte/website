//Cambio de Modo
//Seleccionamos el boton para cambiar el color
const colorModeBtn = document.getElementById('colorMode');
//Seleccionamos el icono del botón para cambiar el color
const colorModeIcon = document.getElementById('colorModeIcon');
//Escuchamos el click del botón
colorModeBtn.addEventListener('click', changeColorMode)
//Función para cambiar el mode de color de la pagina
function changeColorMode(event) {
    //cambiamos/agregamos la clase del body
    document.body.classList.toggle('dark-mode');
    //cambiamos/agregamos la clase del icono
    colorModeIcon.classList.toggle('fa-moon');
    //cambiamos/agregamos la clase al icono
    colorModeIcon.classList.toggle('fa-sun');
    //Si esta en dark-mode 
    if (document.body.classList.contains('dark-mode')) {
        //Habilitamos el localStorage
        localStorage.setItem('darkMode', 'enabled');
    } else {
        //Desactivamos el dark mode del localStorage
        localStorage.setItem('darkMode', 'disabled')
    }
}
//Comprobamos el estado del darkMode en el LocalStorage
if (localStorage.getItem('darkMode') == 'enabled') {
    //removemos la clase del body
    document.body.classList.toggle('dark-mode');
    //cambiamos/agregamos la clase del icono
    colorModeIcon.classList.toggle('fa-moon');
    //cambiamos/agregamos la clase al icono
    colorModeIcon.classList.toggle('fa-sun');
}
function validarFormulario(event) {
    const nombreRol = document.getElementById('nombre_rol');
    const valorNombreRol = nombreRol.value.trim();
    const errorMensaje = document.getElementById('error_nombre_rol');

    // Limpiar mensaje de error previo
    errorMensaje.textContent = '';
    nombreRol.classList.remove('is-invalid');

    // Validar si el campo está vacío
    if (valorNombreRol === '') {
        errorMensaje.textContent = "El campo 'Nombre del Rol' no puede estar vacío.";
        nombreRol.classList.add('is-invalid');
        event.preventDefault(); // Evitar el envío del formulario
        return false;
    }

    // Expresión regular para validar que no haya números
    const contieneNumero = /\d/;
    if (contieneNumero.test(valorNombreRol)) {
        errorMensaje.textContent = "El campo 'Nombre del Rol' no puede contener números.";
        nombreRol.classList.add('is-invalid');
        event.preventDefault(); // Evitar el envío del formulario
        return false;
    }

    return true; // Permite el envío si todo está correcto
}

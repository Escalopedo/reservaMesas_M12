function validarFormulario(event) {
    const ubicacionSala = document.getElementById('ubicacion_sala');
    const imagenFondo = document.getElementById('imagen_fondo');
    
    let valid = true;

    // Limpiar mensajes de error previos
    const errorMessages = document.querySelectorAll('.text-danger');
    errorMessages.forEach(msg => msg.textContent = '');

    // Validar campo Ubicación de la sala
    if (ubicacionSala.value.trim() === '') {
        mostrarError(ubicacionSala, 'La ubicación de la sala no puede estar vacía.');
        valid = false;
    }

    // Si hay algún error, no se envía el formulario
    if (!valid) {
        event.preventDefault();
    }

    return valid;
}

function mostrarError(input, mensaje) {
    // Seleccionar el span correspondiente para el mensaje de error
    const errorElement = document.getElementById(input.id + '_error');
    
    // Mostrar el mensaje de error en el span
    errorElement.textContent = mensaje;
    
    // Añadir la clase 'is-invalid' para mostrar el borde rojo
    input.classList.add('is-invalid');
}

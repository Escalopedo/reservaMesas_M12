// Esta función se ejecutará cuando el formulario se envíe
function validarFormulario(event) {

    const salaSelect = document.getElementById('sala_id');
    const fechaInput = document.getElementById('fecha_reserva');

    // Limpiar mensajes de error previos
    const errorMessages = document.querySelectorAll('.text-danger');

    let valid = true;

    // Validar que la sala esté seleccionada
    if (salaSelect.value === '') {
        mostrarError(salaSelect, 'Por favor, selecciona una sala.');
        valid = false;
    }

    // Validar que la fecha no esté vacía y sea al menos hoy
    const today = new Date().toISOString().split('T')[0]; // Obtener la fecha de hoy en formato YYYY-MM-DD
    if (fechaInput.value === '') {
        mostrarError(fechaInput, 'Por favor, selecciona una fecha.');
        valid = false;
    } else if (fechaInput.value < today) {
        mostrarError(fechaInput, 'La fecha debe ser de hoy como mínimo.');
        valid = false;
    }

    // Si hay errores, evitamos que el formulario se envíe
    if (!valid) {
        event.preventDefault();
    }

    return valid;
}

// Función para mostrar un mensaje de error debajo del campo
function mostrarError(input, mensaje) {
    // Crear el span para el mensaje de error si no existe
    let errorElement = document.getElementById(input.id + '_error');
    if (!errorElement) {
        errorElement = document.createElement('span');
        errorElement.id = input.id + '_error';
        errorElement.classList.add('text-danger');
        input.parentElement.appendChild(errorElement);
    }

    // Mostrar el mensaje de error en el span
    errorElement.textContent = mensaje;
}

// Asignar la función de validación al evento de envío del formulario
document.querySelector('form').addEventListener('submit', validarFormulario);

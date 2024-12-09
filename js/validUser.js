// Validar campos al perder el foco
function validarCampo(campo) {
    const errorId = `${campo.id}-error`; // ID del mensaje de error
    let error = document.getElementById(errorId);

    // Crear mensaje de error si no existe
    if (!error) {
        error = document.createElement('small');
        error.id = errorId;
        error.style.color = 'red';
        campo.parentNode.appendChild(error);
    }

    if (campo.value.trim() === '') {
        error.textContent = "Este campo es obligatorio.";
    }
}

// Validar todos los campos al enviar el formulario
function validarFormulario(event) {
    let esValido = true;

    // Obtener todos los campos
    const campos = document.querySelectorAll('input, select');
    campos.forEach((campo) => {
        if (campo.value.trim() === '') {
            validarCampo(campo);
            esValido = false;
        }
    });

    if (!esValido) {
        event.preventDefault(); // Evitar envío si hay errores
    }

    return esValido;
}

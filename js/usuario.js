function validarFormulario(event) {
    const nombre = document.getElementById('nombre');
    const apellidos = document.getElementById('apellidos');
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const id_rol = document.getElementById('id_rol');
    
    let valid = true;

    // Limpiar mensajes de error previos
    const errorMessages = document.querySelectorAll('.text-danger');
    errorMessages.forEach(msg => msg.textContent = '');
    
    // Validar el campo Nombre
    if (nombre.value.trim() === '') {
        mostrarError(nombre, 'El nombre no puede estar vacío.');
        valid = false;
    } else if (/\d/.test(nombre.value.trim())) {
        mostrarError(nombre, 'El nombre no puede contener números.');
        valid = false;
    }

    // Validar el campo Apellidos
    if (apellidos.value.trim() === '') {
        mostrarError(apellidos, 'Los apellidos no pueden estar vacíos.');
        valid = false;
    } else if (/\d/.test(apellidos.value.trim())) {
        mostrarError(apellidos, 'Los apellidos no pueden contener números.');
        valid = false;
    }

    // Validar el campo Username
    if (username.value.trim() === '') {
        mostrarError(username, 'El username no puede estar vacío.');
        valid = false;
    } else if (/[^a-zA-Z0-9]/.test(username.value.trim())) {
        mostrarError(username, 'El username solo puede contener letras y números.');
        valid = false;
    }

    // Validar el campo Contraseña
    if (password.value.trim() === '') {
        mostrarError(password, 'La contraseña no puede estar vacía.');
        valid = false;
    } else if (password.value.trim().length < 6) {
        mostrarError(password, 'La contraseña debe tener al menos 6 caracteres.');
        valid = false;
    }

    // Validar el campo Rol
    if (id_rol.value == '') {
        mostrarError(id_rol, 'Debe seleccionar un rol.');
        valid = false;
    }

    // Si hay algún error, no se envía el formulario
    if (!valid) {
        event.preventDefault();
    }

    return valid;
}

function mostrarError(input, mensaje) {
    const errorElement = input.nextElementSibling;
    errorElement.textContent = mensaje;
    input.classList.add('is-invalid');
}


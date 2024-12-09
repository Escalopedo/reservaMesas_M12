<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
// Importamos los archivos necesarios
require '../php/conexion.php';
require '../php/roles.php'; 
require_once '../php/functions.php';

$errors = [];

// Validamos que el formulario se envíe correctamente
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $errors[] = 'Solicitud inválida.';
    redirect_with_errors('../php/cerrarSesion.php', $errors); // Redirigir en caso de error
    exit();
}

// Validamos que los campos no estén vacíos
if (empty($_POST['user']) || empty($_POST['contrasena'])) {
    $errors[] = 'Usuario y contraseña son obligatorios.';
    redirect_with_errors('../php/cerrarSesion.php', $errors); // Redirigir en caso de error
    exit();
}

// Recogemos las variables del formulario
$username = htmlspecialchars($_POST['user']);
$password = htmlspecialchars($_POST['contrasena']);

try {
    // Preparamos la consulta con PDO para tbl_usuarios
    $query = "SELECT id_usuario, password, id_rol FROM tbl_usuarios WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    // Ejecutamos la consulta
    $stmt->execute();

    // Obtenemos el resultado
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Verificamos que la contraseña sea correcta
        if (password_verify($password, $row['password'])) {
            // Si la contraseña es correcta, inicializamos la variable de SESSION y redirigimos
            session_start();
            $_SESSION['user_id'] = $row['id_usuario'];
            $_SESSION['user_role'] = $row['id_rol'];  // Guardamos el rol también

            // Redirección dependiendo del rol, haremos la trampa con los ids porqué los sabemos
            if ($row['id_rol'] == 1) {
                echo "<script type='text/javascript'>
                    Swal.fire({
                        title: 'Inicio de sesión',
                        text: '¡Has iniciado sesión correctamente como Camarero!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.href = '../view/mesas.php';  
                    });
                    </script>";
            }   
            if ($row['id_rol'] == 2) {
                echo "<script type='text/javascript'>
                    Swal.fire({
                        title: 'Inicio de sesión',
                        text: '¡Has iniciado sesión correctamente como Administrador!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.href = '../view/admin.php'; 
                    });
                    </script>";
            }
            exit();
        }
    }

    // Si las credenciales son incorrectas
    $errors[] = 'Credenciales incorrectas';
} catch (PDOException $e) {
    // En caso de error, añadir a los errores
    $errors[] = 'Error en la base de datos: ' . $e->getMessage();
}

// Redirigimos en caso de error
redirect_with_errors('../view/index.php', $errors);
?>
</body>
</html>

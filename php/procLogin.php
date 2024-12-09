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
require_once '../php/functions.php';

$errors = [];

// Validamos que el formulario se envíe correctamente
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $errors[] = 'Solicitud inválida.';
    redirect_with_errors('../php/cerrarSesion.php', $errors);
    exit();
}

// Validamos que los campos no estén vacíos
if (empty($_POST['user']) || empty($_POST['contrasena'])) {
    $errors[] = 'Usuario y contraseña son obligatorios.';
    redirect_with_errors('../php/cerrarSesion.php', $errors);
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
            // En caso que sea correcto, inicializamos la variable de SESSION y redirigimos a mesas.php con el ID del usuario
            session_start();
            $_SESSION['user_id'] = $row['id_usuario'];
            $_SESSION['user_role'] = $row['id_rol'];  // Guardamos el rol también, por si se necesita para redirigir a diferentes páginas.

            // Redirección a mesas.php con SweetAlert
            echo "<script type='text/javascript'>
                Swal.fire({
                    title: 'Inicio de sesión',
                    text: '¡Has iniciado sesión correctamente!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location.href = '../view/mesas.php';  // Redirige siempre a mesas.php, puedes personalizar la redirección si se necesitan diferentes roles.
                });
                </script>";
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

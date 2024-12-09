<?php
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../../view/index.php");  // Redirigir si no es Administrador
    exit();
}

require_once '../../conexion.php';

// Si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger el nombre del rol
    $nombre_rol = htmlspecialchars($_POST['nombre_rol']);

    try {
        // Insertar el nuevo rol en la base de datos
        $query = "INSERT INTO tbl_roles (nombre_rol) VALUES (:nombre_rol)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nombre_rol', $nombre_rol);
        $stmt->execute();

        // Redirigir a la página de administración después de añadir el rol
        header('Location: ../../../view/admin.php'); 
        exit();
    } catch (PDOException $e) {
        echo "Error al añadir rol: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Rol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h2>Añadir Nuevo Rol</h2>
        <form action="addrol.php" method="POST">
            <div class="mb-3">
                <label for="nombre_rol" class="form-label">Nombre del Rol:</label>
                <input type="text" name="nombre_rol" id="nombre_rol" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Añadir Rol</button>
        </form>
        <a href="../../../view/admin.php" class="btn btn-secondary mt-3">Volver a Administración</a>
    </div>
</body>
</html>

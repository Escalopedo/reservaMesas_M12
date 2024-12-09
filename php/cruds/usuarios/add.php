<?php
session_start();
if ($_SESSION['user_role'] != 2) {
    header("Location: ../../view/index.php");
    exit();
}

require_once '../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellidos = htmlspecialchars($_POST['apellidos']);
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $id_rol = (int)$_POST['id_rol'];

    try {
        $query = "INSERT INTO tbl_usuarios (nombre_usuario, apellidos_usuario, username, password, id_rol) 
                  VALUES (:nombre, :apellidos, :username, :password, :id_rol)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id_rol', $id_rol);
        $stmt->execute();

        header('Location: ../../../view/admin.php'); 
    } catch (PDOException $e) {
        echo "Error al añadir usuario: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../css/cuestionarios.css">
    <script src="../../../js/validUser.js" defer></script>
    <title>Añadir Usuario</title>
</head>
<body>

    <div class="container mt-5">
        <h2>Añadir Usuario</h2>
        <form action="add.php" method="POST" onsubmit="return validarFormulario(event)">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control">
            </div>

            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos:</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control">
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" id="username" class="form-control">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>

            <div class="mb-3">
                <label for="id_rol" class="form-label">Rol:</label>
                <select name="id_rol" id="id_rol" class="form-control">
                    <option value="1">Camarero</option>
                    <option value="2">Administrador</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Añadir Usuario</button>
        </form>
        <a href="../../../view/admin.php" class="btn btn-secondary mt-3">Volver a Administración</a>

    </div>

</body>
</html>

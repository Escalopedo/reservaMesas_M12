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
    <title>Añadir Usuario</title>
</head>
<body>

    <h2>Añadir Usuario</h2>
    <form action="add.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required><br>

        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" id="apellidos" required><br>

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" required><br>

        <label for="id_rol">Rol:</label>
        <select name="id_rol" id="id_rol" required>
            <option value="1">Camarero</option>
            <option value="2">Administrador</option>
        </select><br>

        <button type="submit">Añadir Usuario</button>
    </form>

</body>
</html>

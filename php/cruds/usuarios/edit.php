<?php
session_start();
if ($_SESSION['user_role'] != 2) {
    header("Location: ../../view/index.php");
    exit();
}

require_once '../../conexion.php';

if (isset($_GET['id'])) {
    $id_usuario = (int)$_GET['id'];

    // Obtener los datos del usuario a editar
    $query = "SELECT * FROM tbl_usuarios WHERE id_usuario = :id_usuario";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellidos = htmlspecialchars($_POST['apellidos']);
    $username = htmlspecialchars($_POST['username']);
    $id_rol = (int)$_POST['id_rol'];

    try {
        $query = "UPDATE tbl_usuarios SET nombre_usuario = :nombre, apellidos_usuario = :apellidos, 
                  username = :username, id_rol = :id_rol WHERE id_usuario = :id_usuario";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':id_rol', $id_rol);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();

        header('Location: ../../../view/admin.php'); 
    } catch (PDOException $e) {
        echo "Error al actualizar usuario: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
</head>
<body>

    <h2>Editar Usuario</h2>
    <form action="edit.php?id=<?= $usuario['id_usuario'] ?>" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" required><br>

        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" id="apellidos" value="<?= htmlspecialchars($usuario['apellidos_usuario']) ?>" required><br>

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?= htmlspecialchars($usuario['username']) ?>" required><br>

        <label for="id_rol">Rol:</label>
        <select name="id_rol" id="id_rol" required>
            <option value="1" <?= $usuario['id_rol'] == 1 ? 'selected' : '' ?>>Camarero</option>
            <option value="2" <?= $usuario['id_rol'] == 2 ? 'selected' : '' ?>>Administrador</option>
        </select><br>

        <button type="submit">Actualizar Usuario</button>
    </form>

</body>
</html>

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../css/cuestionarios.css">
    <title>Editar Usuario</title>
</head>
<body>

    <div class="container mt-5">
        <h2>Editar Usuario</h2>
        <form action="edit.php?id=<?= $usuario['id_usuario'] ?>" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos:</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" value="<?= htmlspecialchars($usuario['apellidos_usuario']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($usuario['username']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="id_rol" class="form-label">Rol:</label>
                <select name="id_rol" id="id_rol" class="form-control" required>
                    <option value="1" <?= $usuario['id_rol'] == 1 ? 'selected' : '' ?>>Camarero</option>
                    <option value="2" <?= $usuario['id_rol'] == 2 ? 'selected' : '' ?>>Administrador</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
        </form>
        <a href="../../../view/admin.php" class="btn btn-secondary mt-3">Volver a Administración</a>
    </div>

</body>
</html>

<?php
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../view/index.php");  // Redirigir si no es Administrador
    exit();
}

require_once '../php/conexion.php'; // Conectar con la base de datos

// Obtener todos los usuarios
$query = "SELECT * FROM tbl_usuarios";
$stmt = $conn->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/mesas.css">
</head>
<body>
    <!-- Cabecera -->
        <header id="container_header">
            <!-- Contenedor del usuario -->
            <div id="container-username">
                <!-- icono del usuario  -->
                <div id="icon_profile_header">
                    <img src="../img/logoSinFondo.png" alt="" id="icon_profile">
                </div>
            </div>

            <!-- Contenedor del título de la página -->
            <div id="container_title_header">
                <span class="span_subtitle">ADMIN</span>
            </div>

            <!-- Contenedor de navegación -->
            <nav id="nav_header">
                <a href="./mesas.php" class="btn btn-danger me-2 btn_custom_logOut">Mesas</a>
                <a href="../php/cerrarSesion.php" class="btn btn-danger btn_custom_logOut m-1">Cerrar sesión</a>
            </nav>
        </header>

<!-- Tabla de usuarios -->
<div class="container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Username</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                        <td><?= htmlspecialchars($usuario['nombre_usuario']) ?></td>
                        <td><?= htmlspecialchars($usuario['apellidos_usuario']) ?></td>
                        <td><?= htmlspecialchars($usuario['username']) ?></td>
                        <td><?= htmlspecialchars($usuario['id_rol']) ?></td>
                        <td>
                            <a href="../php/cruds/usuarios/edit.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="../php/cruds/usuarios/delete.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <a href="../php/cruds/usuarios/add.php">Añadir Usuario</a>

</body>
</html>
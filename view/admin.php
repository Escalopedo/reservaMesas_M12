<?php
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../view/index.php");  // Redirigir si no es Administrador
    exit();
}

require_once '../php/conexion.php'; // Conectar con la base de datos

// Obtener todos los usuarios
$query_usuarios = "
    SELECT u.id_usuario, u.nombre_usuario, u.apellidos_usuario, u.username, r.nombre_rol 
    FROM tbl_usuarios u
    JOIN tbl_roles r ON u.id_rol = r.id_rol
";
$stmt_usuarios = $conn->prepare($query_usuarios);
$stmt_usuarios->execute();
$usuarios = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);

// Obtener todos los roles
$query_roles = "SELECT * FROM tbl_roles";
$stmt_roles = $conn->prepare($query_roles);
$stmt_roles->execute();
$roles = $stmt_roles->fetchAll(PDO::FETCH_ASSOC);

// Obtener todas las mesas junto con el nombre de la sala
$query_mesas = "SELECT m.id_mesa, m.numero_sillas_mesa, s.ubicacion_sala 
                FROM tbl_mesa m
                JOIN tbl_sala s ON m.id_sala = s.id_sala";
$stmt_mesas = $conn->prepare($query_mesas);
$stmt_mesas->execute();
$mesas = $stmt_mesas->fetchAll(PDO::FETCH_ASSOC);

// Obtener todas las salas
$query_salas = "SELECT * FROM tbl_sala";
$stmt_salas = $conn->prepare($query_salas);
$stmt_salas->execute();
$salas = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/mesas.css">
</head>
<body>
    <!-- Cabecera -->
    <header id="container_header">
        <div id="container-username">
            <div id="icon_profile_header">
                <img src="../img/logoSinFondo.png" alt="" id="icon_profile">
            </div>
        </div>
        <div id="container_title_header">
            <span class="span_subtitle">ADMIN</span>
        </div>
        <nav id="nav_header">
            <a href="./mesas.php" class="btn btn-danger me-2 btn_custom_logOut">Mesas</a>
            <a href="../php/cerrarSesion.php" class="btn btn-danger btn_custom_logOut m-1">Cerrar sesión</a>
        </nav>
    </header>

    <div class="container">
        <!-- Tabla de usuarios -->
        <h2 class="my-4">Gestión de Usuarios</h2>
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
            <td><?= htmlspecialchars($usuario['nombre_rol']) ?></td>
            <td>
                <a href="../php/cruds/usuarios/edit.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="../php/cruds/usuarios/delete.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
            </tbody>

        </table>

        <a href="../php/cruds/usuarios/add.php" class="btn btn-primary my-3">Añadir Usuario</a>

        <!-- Tabla de roles -->
        <h2 class="my-4">Gestión de Roles</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= htmlspecialchars($role['id_rol']) ?></td>
                        <td><?= htmlspecialchars($role['nombre_rol']) ?></td>
                        <td>
                            <a href="../php/cruds/usuarios/editrol.php?id=<?= $role['id_rol'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="../php/cruds/usuarios/deleterol.php?id=<?= $role['id_rol'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../php/cruds/usuarios/addrol.php" class="btn btn-primary my-3">Añadir Rol</a>

        <hr>

        <!-- Tabla de mesas -->
        <h2 class="my-4">Gestión de Mesas</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número de Sillas</th>
                    <th>Sala</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mesas as $mesa): ?>
                    <tr>
                        <td><?= htmlspecialchars($mesa['id_mesa']) ?></td>
                        <td><?= htmlspecialchars($mesa['numero_sillas_mesa']) ?></td>
                        <td><?= htmlspecialchars($mesa['ubicacion_sala']) ?></td> <!-- Mostrar el nombre de la sala -->
                        <td>
                            <a href="../php/cruds/mesas/edit.php?id=<?= $mesa['id_mesa'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="../php/cruds/mesas/delete.php?id=<?= $mesa['id_mesa'] ?>" class="btn btn-danger btn-sm" >Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../php/cruds/mesas/add.php" class="btn btn-primary my-3">Añadir Mesa</a>

        <!-- Tabla de salas -->
        <h2 class="my-4">Gestión de Salas</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ubicación de Sala</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salas as $sala): ?>
                    <tr>
                        <td><?= htmlspecialchars($sala['id_sala']) ?></td>
                        <td><?= htmlspecialchars($sala['ubicacion_sala']) ?></td>
                        <td>
                            <a href="../php/cruds/mesas/editsala.php?id=<?= $sala['id_sala'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="../php/cruds/mesas/deletesala.php?id=<?= $sala['id_sala'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../php/cruds/mesas/addsala.php" class="btn btn-primary my-3">Añadir Sala</a>
    </div>

</body>
</html>

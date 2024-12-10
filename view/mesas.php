<?php
// Iniciamos la sesión
session_start();

// Verificamos si la sesión del camarero está activa
if (empty($_SESSION['user_id'])) {
    // Si no está activo, redirigimos a la página de inicio de sesión
    header("Location: ./index.php");
    exit();
}

// Incluimos el archivo de conexión a la base de datos
require '../php/conexion.php';
require_once '../php/functions.php';

// Obtenemos el ID del camarero desde la sesión
$id_camarero = $_SESSION['user_id'];

// Función para obtener información del camarero
$info_waiter = get_info_waiter_bbdd($conn, $id_camarero);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/mesas.css">
</head>
<body>
    <!-- Cabecera -->
    <header id="container_header">
        <div id="container-username">
            <div id="icon_profile_header">
                <img src="../img/logoSinFondo.png" alt="" id="icon_profile">
            </div>
            <div id="username_profile_header">
                <p id="p_username_profile"><?php echo htmlspecialchars($info_waiter['username']) ?></p>
                <span class="span_subtitle"><?php echo htmlspecialchars($info_waiter['name']) . " " . htmlspecialchars($info_waiter['surname']) ?></span>
            </div>
        </div>

        <div id="container_title_header">
            <h1 id="title_header"><strong>Dinner At Westfield</strong></h1>
            <span class="span_subtitle">Gestión de salas</span>
        </div>

        <nav id="nav_header">
            <a href="./historico.php" class="btn btn-danger me-2 btn_custom_logOut">Histórico</a>
            <a href="./reservas.php" class="btn btn-danger me-2 btn_custom_logOut">Reservar</a>
            <a href="../php/cerrarSesion.php" class="btn btn-danger btn_custom_logOut m-1">Cerrar sesión</a>
        </nav>
    </header>

    <main id="mesas_main">
        <div id="mapaRestaurante_contenedor">
            <!-- Aquí mostramos solo las salas -->
            <div id="divSalas">
                <?php
                    // Consulta para obtener las salas disponibles
                    $query_salas = "SELECT * FROM tbl_sala";
                    $stmt_salas = $conn->prepare($query_salas);
                    $stmt_salas->execute();
                    $salas = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);

                    // Mostrar cada sala
                    foreach ($salas as $sala) {
                        echo "<div class='sala' style='cursor: pointer;'>";
                        echo "<a href='salas.php?id_sala=" . $sala['id_sala'] . "'>";
                        echo "<h4>" . htmlspecialchars($sala['ubicacion_sala']) . "</h4>";
                        echo "</a>";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
    </main>

<script src="../js/modal.js"></script>
</body>
</html>

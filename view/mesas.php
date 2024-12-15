<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header("Location: ./index.php");
    exit();
}

require '../php/conexion.php';
require_once '../php/functions.php';

$id_camarero = $_SESSION['user_id'];
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
            <div id="divSalas">
                <?php
                $query_salas = "SELECT * FROM tbl_sala";
                $stmt_salas = $conn->prepare($query_salas);
                $stmt_salas->execute();
                $salas = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);

                foreach ($salas as $sala) {
                    $ubicacion_sala = isset($sala['ubicacion_sala']) ? htmlspecialchars($sala['ubicacion_sala']) : 'Ubicación no disponible';
                    $imagen_fondo = isset($sala['imagen_fondo']) ? "../" . htmlspecialchars($sala['imagen_fondo']) : '../img/default.jpg'; // Imagen predeterminada
                
                    echo "<div class='sala' style='cursor: pointer; background-image: url($imagen_fondo); background-size: cover; background-position: center;'>";
                    echo "<a href='salas.php?id_sala=" . $sala['id_sala'] . "'>";
                    echo "<h4>" . $ubicacion_sala . "</h4>";
                    echo "</a>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </main>
    <div>
        <br>
        <br>
    </div>

</body>
</html>

<?php
session_start();

// Verificamos si la sesión del camarero está activa
if (empty($_SESSION['user_id'])) {
    header("Location: ./index.php");
    exit();
}

require '../php/conexion.php';
require_once '../php/functions.php';
require '../php/estadoMesaRecuperar.php'; // Esto recupera el estado de las mesas

// Recuperar los estados de las mesas desde la sesión
$ocupaciones = isset($_SESSION['ARRAYocupaciones']) ? $_SESSION['ARRAYocupaciones'] : [];

$id_sala = isset($_GET['id_sala']) ? $_GET['id_sala'] : null;
if ($id_sala === null) {
    header("Location: ./index.php");
    exit();
}

// Obtenemos el ID del camarero desde la sesión
$id_camarero = $_SESSION['user_id'];

// Función para obtener información del camarero
$info_waiter = get_info_waiter_bbdd($conn, $id_camarero);

// Obtener las mesas de la sala
$query_mesas = "SELECT * FROM tbl_mesa WHERE id_sala = :id_sala";
$stmt_mesas = $conn->prepare($query_mesas);
$stmt_mesas->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
$stmt_mesas->execute();
$mesas = $stmt_mesas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesas de Sala</title>
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
            <div id="divMesas">
                <h3>Mesas de la Sala</h3>
                <?php
                    if (count($mesas) > 0) {
                        foreach ($mesas as $mesa) {
                            $mesa_id = $mesa['id_mesa'];
                            $estado_mesa = isset($ocupaciones[$mesa_id]) ? $ocupaciones[$mesa_id] : 'Disponible';
                            echo "</br>"; 
                            echo "</br>"; 
                            echo "</br>"; 


                            echo "<div class='mesa' data-id-mesa='$mesa_id' style='cursor: pointer;'>";
                            echo "<h4>" . htmlspecialchars('Mesa ' . $mesa_id) . "</h4>";
                            echo "<p>Capacidad: " . htmlspecialchars($mesa['numero_sillas_mesa']) . " personas</p>";
                            echo "<p><strong>Estado: </strong>" . htmlspecialchars($estado_mesa) . "</p>";

                            // Mostrar el botón para ocupar o liberar según el estado
                            if ($estado_mesa === 'Disponible') {
                                echo "<a href='../php/reservaMesas.php?id=$mesa_id' class='btn btn-success'>Ocupar</a>";
                            } elseif ($estado_mesa === 'Ocupado') {
                                echo "<a href='../php/liberarMesas.php?id=$mesa_id' class='btn btn-danger'>Liberar</a>";
                            }
                            
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No hay mesas en esta sala.</p>";
                    }
                ?>
            </div>
        </div>
    </main>

    <script src="../js/modal.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
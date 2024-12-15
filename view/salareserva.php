<?php
// Iniciar sesión y verificar el estado del usuario
session_start();
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Conectar a la base de datos
require '../php/conexion.php';
require '../php/functions.php';

// Obtenemos el ID del camarero desde la sesión
$id_camarero = $_SESSION['user_id'];

// Función para obtener información del camarero
$info_waiter = get_info_waiter_bbdd($conn, $id_camarero);

// Obtener la sala y la fecha desde el formulario
$sala_id = $_GET['sala_id'];
$fecha_reserva = $_GET['fecha_reserva'];

// Obtener las franjas horarias disponibles
$query_horarios = "SELECT * FROM tbl_horarios";
$stmt = $conn->prepare($query_horarios);
$stmt->execute();
$horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar mesas libres y ocupadas en la sala y fecha seleccionada
$mesas_libres = [];
$mesas_ocupadas = [];

foreach ($horarios as $horario) {
    $hora_inicio = $horario['hora_inicio'];
    $hora_fin = $horario['hora_fin'];

    // Consulta para obtener las mesas libres
    $query_mesas_libres = "
        SELECT m.id_mesa, m.numero_sillas_mesa FROM tbl_mesa m
        WHERE m.id_sala = :sala_id
        AND m.id_mesa NOT IN (
            SELECT o.id_mesa FROM tbl_ocupacion o
            JOIN tbl_reservas r ON o.id_reserva = r.id_reserva
            WHERE r.fecha_reserva = :fecha_reserva
            AND r.id_horario = :horario_id
            AND o.estado_ocupacion != 'Cancelada'
        )
    ";
    $stmt_mesas_libres = $conn->prepare($query_mesas_libres);
    $stmt_mesas_libres->execute([
        ':sala_id' => $sala_id,
        ':fecha_reserva' => $fecha_reserva,
        ':horario_id' => $horario['id_horario']
    ]);
    $mesas_libres[$horario['id_horario']] = $stmt_mesas_libres->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obtener las mesas ocupadas
    $query_mesas_ocupadas = "
        SELECT m.id_mesa, m.numero_sillas_mesa, r.id_reserva
        FROM tbl_mesa m
        JOIN tbl_ocupacion o ON m.id_mesa = o.id_mesa
        JOIN tbl_reservas r ON o.id_reserva = r.id_reserva
        WHERE m.id_sala = :sala_id
        AND r.fecha_reserva = :fecha_reserva
        AND r.id_horario = :horario_id
        AND o.estado_ocupacion != 'Cancelada'
    ";
    $stmt_mesas_ocupadas = $conn->prepare($query_mesas_ocupadas);
    $stmt_mesas_ocupadas->execute([
        ':sala_id' => $sala_id,
        ':fecha_reserva' => $fecha_reserva,
        ':horario_id' => $horario['id_horario']
    ]);
    $mesas_ocupadas[$horario['id_horario']] = $stmt_mesas_ocupadas->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesas Disponibles</title>
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
        <span class="span_subtitle">Gestión de mesas</span>
    </div>

    <nav id="nav_header">
        <a href="./historico.php" class="btn btn-danger me-2 btn_custom_logOut">Histórico</a>
        <a href="./mesas.php" class="btn btn-danger me-2 btn_custom_logOut">Mesas</a>
        <a href="../php/cerrarSesion.php" class="btn btn-danger btn_custom_logOut m-1">Cerrar sesión</a>
    </nav>
</header>

<div class="container mt-5">
    <h1 class="text-center">Mesas en Sala <?php echo htmlspecialchars($sala_id); ?> para el <?php echo htmlspecialchars($fecha_reserva); ?></h1>
    <br>
    <br>

    <?php foreach ($horarios as $horario): ?>
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h2 class="card-title">Franja Horaria: <?php echo $horario['hora_inicio'] . ' - ' . $horario['hora_fin']; ?></h2>
            </div>
            <div class="card-body">
                <!-- Mesas Libres -->
                <h4>Mesas Libres</h4>
                <?php if (!empty($mesas_libres[$horario['id_horario']])): ?>
                    <ul class="list-group">
                        <?php foreach ($mesas_libres[$horario['id_horario']] as $mesa): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Mesa ID: <?php echo $mesa['id_mesa']; ?></strong> 
                                    - Sillas: <?php echo $mesa['numero_sillas_mesa']; ?>
                                </div>
                                <a href="../php/reservas/confirmar_reserva.php?id_mesa=<?php echo $mesa['id_mesa']; ?>&fecha_reserva=<?php echo $fecha_reserva; ?>&horario_id=<?php echo $horario['id_horario']; ?>" class="btn btn-success btn-sm">Reservar</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No hay mesas disponibles para esta franja horaria.</p>
                <?php endif; ?>

                <!-- Mesas Ocupadas -->
                <h4 class="mt-4">Mesas Ocupadas</h4>
                <?php if (!empty($mesas_ocupadas[$horario['id_horario']])): ?>
                    <ul class="list-group">
                        <?php foreach ($mesas_ocupadas[$horario['id_horario']] as $mesa): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Mesa ID: <?php echo $mesa['id_mesa']; ?></strong> 
                                    - Sillas: <?php echo $mesa['numero_sillas_mesa']; ?>
                                </div>
                                <a href="../php/reservas/cancelar_reserva.php?id_reserva=<?php echo $mesa['id_reserva']; ?>" class="btn btn-danger btn-sm">Cancelar Reserva</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No hay mesas ocupadas para esta franja horaria.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<br>
<br>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

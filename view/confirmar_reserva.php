<?php
// Iniciar sesión y verificar el estado del usuario
session_start();
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Conectar a la base de datos
require '../php/conexion.php';

// Obtener los parámetros de la URL
$id_mesa = $_GET['id_mesa'] ?? null;
$fecha_reserva = $_GET['fecha_reserva'] ?? null;
$id_horario = $_GET['horario_id'] ?? null; // Asegúrate de que este valor se pase correctamente

// Obtener el ID del usuario (que es el camarero que está realizando la reserva)
$id_usuario = $_SESSION['user_id'];

// Obtener las horas de inicio y fin para el horario seleccionado
$query_horario = "
    SELECT hora_inicio, hora_fin
    FROM tbl_horarios
    WHERE id_horario = :id_horario
";
$stmt_horario = $conn->prepare($query_horario);
$stmt_horario->execute([':id_horario' => $id_horario]);

$horario = $stmt_horario->fetch(PDO::FETCH_ASSOC);

if (!$horario) {
    die('Horario no válido.');
}

// Asignar las horas de inicio y fin a las variables
$hora_inicio = $horario['hora_inicio'];
$hora_fin = $horario['hora_fin'];

// Verificar si la mesa, fecha y horario son válidos
$query_verificar = "
    SELECT m.id_mesa, h.id_horario
    FROM tbl_mesa m
    JOIN tbl_horarios h ON h.id_horario = :id_horario
    WHERE m.id_mesa = :id_mesa
    AND h.hora_inicio <= :hora_inicio
    AND h.hora_fin >= :hora_fin
";
$stmt_verificar = $conn->prepare($query_verificar);
$stmt_verificar->execute([
    ':id_mesa' => $id_mesa,
    ':id_horario' => $id_horario,
    ':hora_inicio' => $fecha_reserva . ' ' . $hora_inicio, // Fecha + Hora de inicio
    ':hora_fin' => $fecha_reserva . ' ' . $hora_fin // Fecha + Hora de fin
]);

$mesa_valida = $stmt_verificar->fetch(PDO::FETCH_ASSOC);

if (!$mesa_valida) {
    // Si no es una mesa válida, redirigir o mostrar un mensaje de error
    die('Mesa no válida o ya ocupada en el horario seleccionado');
}

// Insertar la nueva reserva en la tabla tbl_reservas
$query_reserva = "
    INSERT INTO tbl_reservas (id_mesa, id_usuario, fecha_reserva, id_horario, estado_reserva)
    VALUES (:id_mesa, :id_usuario, :fecha_reserva, :id_horario, 'Confirmada')
";
$stmt_reserva = $conn->prepare($query_reserva);
$stmt_reserva->execute([
    ':id_mesa' => $id_mesa,
    ':id_usuario' => $id_usuario,
    ':fecha_reserva' => $fecha_reserva,
    ':id_horario' => $id_horario
]);

// Obtener el ID de la nueva reserva
$id_reserva = $conn->lastInsertId();

// Registrar la ocupación de la mesa
$query_ocupacion = "
    INSERT INTO tbl_ocupacion (id_mesa, id_usuario, id_reserva, estado_ocupacion)
    VALUES (:id_mesa, :id_usuario, :id_reserva, 'Reservada')
";
$stmt_ocupacion = $conn->prepare($query_ocupacion);
$stmt_ocupacion->execute([
    ':id_mesa' => $id_mesa,
    ':id_usuario' => $id_usuario,
    ':id_reserva' => $id_reserva
]);

// Redirigir a una página de confirmación o mostrar mensaje de éxito
header("Location: reserva_confirmada.php?id_reserva=$id_reserva");
exit();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/mesas.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Confirmar Reserva</h2>
        
        <?php if (isset($mesa_valida) && !$mesa_valida): ?>
            <div class="alert alert-danger">
                <strong>Error!</strong> La mesa seleccionada ya está ocupada en el horario indicado. Intenta con otra mesa o horario.
            </div>
        <?php else: ?>
            <div class="alert alert-success">
                <strong>¡Reserva Confirmada!</strong> La reserva se ha realizado correctamente.
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <h5>Detalles de la reserva:</h5>
            <p><strong>Mesa:</strong> <?php echo htmlspecialchars($id_mesa); ?></p>
            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($fecha_reserva); ?></p>
            <p><strong>Horario:</strong> <?php echo htmlspecialchars($hora_inicio) . ' - ' . htmlspecialchars($hora_fin); ?></p>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <a href="index.php" class="btn btn-primary">Volver al inicio</a>
            <a href="ver_reservas.php" class="btn btn-secondary ms-2">Ver Reservas</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

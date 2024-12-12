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


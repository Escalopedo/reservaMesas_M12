<?php
// Iniciar sesión
session_start();
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Conectar a la base de datos
require '../conexion.php';


// Obtener el ID de la reserva
$id_reserva = $_GET['id_reserva'];

// Obtener los detalles de la reserva
$query_reserva = "
    SELECT r.fecha_reserva, r.id_horario, h.hora_inicio, h.hora_fin, m.numero_sillas_mesa, s.ubicacion_sala
    FROM tbl_reservas r
    JOIN tbl_horarios h ON r.id_horario = h.id_horario
    JOIN tbl_mesa m ON r.id_mesa = m.id_mesa
    JOIN tbl_sala s ON m.id_sala = s.id_sala
    WHERE r.id_reserva = :id_reserva
";
$stmt_reserva = $conn->prepare($query_reserva);
$stmt_reserva->execute([':id_reserva' => $id_reserva]);

$reserva = $stmt_reserva->fetch(PDO::FETCH_ASSOC);

if (!$reserva) {
    die("Reserva no encontrada.");
}

// Alerta de confirmación de reserva
echo "<script>
    alert('Reserva Confirmada para la sala " . htmlspecialchars($reserva['ubicacion_sala']) . " con " . htmlspecialchars($reserva['numero_sillas_mesa']) . " sillas. Fecha: " . htmlspecialchars($reserva['fecha_reserva']) . " de " . htmlspecialchars($reserva['hora_inicio']) . " a " . htmlspecialchars($reserva['hora_fin']) . "!');
    window.location.href = '../../view/reservas.php';
</script>";
exit();
?>

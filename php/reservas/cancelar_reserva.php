<?php
// Iniciar sesión y verificar el estado del usuario
session_start();
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Conectar a la base de datos
require '../conexion.php';

// Verificar si se recibió el ID de la reserva
if (!isset($_GET['id_reserva'])) {
    die("Error: ID de reserva no especificado.");
}

$id_reserva = $_GET['id_reserva'];

try {
    // Actualizar el estado de la ocupación a 'Cancelada'
    $query_cancelar = "
        UPDATE tbl_ocupacion o
        JOIN tbl_reservas r ON o.id_reserva = r.id_reserva
        SET o.estado_ocupacion = 'Cancelada'
        WHERE r.id_reserva = :id_reserva
    ";

    $stmt_cancelar = $conn->prepare($query_cancelar);
    $stmt_cancelar->execute([':id_reserva' => $id_reserva]);

    // Redirigir a la página de gestión de mesas con un mensaje de éxito
    echo "<script>
        alert('Reserva cancelada con éxito.');
    window.location.href = '../../view/reservas.php';
    </script>";
    exit();

} catch (PDOException $e) {
    // Manejo de errores
    echo "<script>
        alert('Error al cancelar la reserva: " . $e->getMessage() . "');
    window.location.href = '../../view/reservas.php';
    </script>";
    exit();
}
?>

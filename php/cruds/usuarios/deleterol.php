<?php
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../../view/index.php");  // Redirigir si no es Administrador
    exit();
}

require_once '../../conexion.php';

// Verificar si se pasó el ID del rol
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../../view/admin.php");  // Redirigir si no se pasa un ID válido
    exit();
}

$id_rol = $_GET['id'];

// Eliminar el rol de la base de datos
try {
    $query = "DELETE FROM tbl_roles WHERE id_rol = :id_rol";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_rol', $id_rol);
    $stmt->execute();

    // Redirigir a la página de administración después de eliminar
    header('Location: ../../../view/admin.php'); 
    exit();
} catch (PDOException $e) {
    echo "Error al eliminar rol: " . $e->getMessage();
}
?>

<?php
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../../view/index.php");
    exit();
}

require_once '../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el nombre de la sala desde el formulario
    $ubicacion_sala = $_POST['ubicacion_sala'];

    // Insertar la nueva sala en la base de datos
    $query_insert = "INSERT INTO tbl_sala (ubicacion_sala) VALUES (:ubicacion_sala)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bindParam(':ubicacion_sala', $ubicacion_sala);

    if ($stmt_insert->execute()) {
        header('Location: ../../../view/admin.php'); 
        exit();
    } else {
        $error = "Hubo un error al añadir la sala.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Sala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Añadir Sala</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="ubicacion_sala" class="form-label">Ubicación de la Sala</label>
                <input type="text" name="ubicacion_sala" id="ubicacion_sala" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Añadir Sala</button>
        </form>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3"><?= $error ?></div>
        <?php endif; ?>
    </div>
</body>
</html>

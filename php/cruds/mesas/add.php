<?php
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../../view/index.php");
    exit();
}

require_once '../../conexion.php';

// Obtener todas las salas para elegir en el formulario
$query_salas = "SELECT * FROM tbl_sala";
$stmt_salas = $conn->prepare($query_salas);
$stmt_salas->execute();
$salas = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $numero_sillas = $_POST['numero_sillas'];
    $id_sala = $_POST['id_sala'];

    // Insertar la nueva mesa en la base de datos
    $query_insert = "INSERT INTO tbl_mesa (id_sala, numero_sillas_mesa) VALUES (:id_sala, :numero_sillas)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bindParam(':id_sala', $id_sala);
    $stmt_insert->bindParam(':numero_sillas', $numero_sillas);

    if ($stmt_insert->execute()) {
        header('Location: ../../../view/admin.php'); 
        exit();
    } else {
        $error = "Hubo un error al añadir la mesa.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Mesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/cuestionarios.css">
    <script src="../../../js/validUser.js" defer></script>
</head>
<body>
    <div class="container">
        <h2>Añadir Mesa</h2>
        <form method="POST" onsubmit="return validarFormulario(event)">
            <div class="mb-3">
                <label for="numero_sillas" class="form-label">Número de Sillas</label>
                <input type="number" name="numero_sillas" id="numero_sillas" class="form-control">
            </div>

            <div class="mb-3">
                <label for="id_sala" class="form-label">Sala</label>
                <select name="id_sala" id="id_sala" class="form-control" required>
                    <?php foreach ($salas as $sala): ?>
                        <option value="<?= $sala['id_sala'] ?>"><?= $sala['ubicacion_sala'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Añadir Mesa</button>
        </form>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3"><?= $error ?></div>
        <?php endif; ?>
        <a href="../../../view/admin.php" class="btn btn-secondary mt-3">Volver a Administración</a>

    </div>
</body>
</html>
